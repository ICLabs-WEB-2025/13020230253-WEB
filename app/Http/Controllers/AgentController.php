<?php

namespace App\Http\Controllers;

use App\Models\House;
use App\Models\Offer;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; // Pastikan ini di-import

class AgentController extends Controller
{
    /**
     * Tampilkan daftar rumah dan percakapan agen.
     */
    public function index(Request $request)
    {
        $agentId = Auth::id(); // Dapatkan ID agen yang sedang terautentikasi

        $search = $request->query('search');
        $sortBy = $request->query('sort_by', 'created_at'); // Default sort by created_at (terbaru)
        $sortOrder = $request->query('sort_order', 'desc'); // Default sort order (terbaru ke terlama)

        // Validasi kolom pengurutan untuk mencegah SQL injection
        $validSortColumns = ['price', 'title', 'status', 'created_at'];
        $sortBy = in_array($sortBy, $validSortColumns) ? $sortBy : 'created_at';
        $sortOrder = in_array($sortOrder, ['asc', 'desc']) ? $sortOrder : 'desc';

        // Ambil daftar rumah milik agen
        $houses = House::where('agent_id', $agentId)
            ->when($search, function ($query, $search) {
                return $query->where('title', 'like', "%{$search}%");
            })
            ->orderBy($sortBy, $sortOrder)
            ->paginate(9); // Mengubah paginate menjadi 9 agar sesuai dengan layout 3x3

        // --- Data untuk Bagian Ikhtisar Dasbor (Overview Section) ---
        $totalHouses = House::where('agent_id', $agentId)->count();
        $availableHouses = House::where('agent_id', $agentId)->where('status', 'Tersedia')->count();
        $soldHouses = House::where('agent_id', $agentId)->where('status', 'Terjual')->count();
        // Untuk `unread_messages`, akan dihitung di JavaScript saat `fetchConversations`

        // Ambil daftar percakapan untuk agen
        $conversations = Conversation::where('agent_id', $agentId)
            ->with(['buyer', 'house', 'messages' => function ($query) {
                $query->latest()->limit(1); // Ambil pesan terbaru saja
            }])
            // Menghitung pesan belum dibaca dari buyer (bukan dari agen itu sendiri)
            ->withCount(['messages as unread_count' => function ($query) {
                $query->where('is_read', false)
                      ->where('sender_type', 'buyer');
            }])
            ->orderByDesc('updated_at') // Urutkan berdasarkan aktivitas terbaru
            ->get(); // Ambil semua percakapan, tidak perlu paginate untuk list chat box kecil

        return view('agent.index', compact('houses', 'search', 'sortBy', 'sortOrder', 'conversations', 'totalHouses', 'availableHouses', 'soldHouses'));
    }

    /**
     * Tampilkan formulir untuk membuat rumah baru.
     */
    public function create()
    {
        return view('agent.create');
    }

    /**
     * Simpan rumah yang baru dibuat ke penyimpanan.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Pastikan format dan ukuran
        ]);

        try {
            $directory = 'houses';
            // Pastikan direktori ada, buat jika belum
            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
                Log::info('Direktori dibuat: ' . $directory . ' di disk publik');
            }

            $file = $request->file('photo');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension(); // Gunakan uniqid untuk nama file yang lebih unik
            $path = $file->storeAs($directory, $fileName, 'public');

            if (!$path || !Storage::disk('public')->exists($path)) {
                throw new \Exception('Gagal menyimpan file gambar. Path: ' . $path);
            }

            Log::info('File diunggah: ' . $fileName . ' ke ' . $path);

            $house = House::create([
                'agent_id' => Auth::id(),
                'title' => $validated['title'],
                'price' => $validated['price'],
                'photo_path' => 'public/' . $path, // Simpan path lengkap untuk akses mudah
                'status' => 'Tersedia', // Status default
            ]);

            Log::info('Rumah dibuat dengan ID: ' . $house->id . ' dan photo_path: ' . $house->photo_path);

            return redirect()->route('agent.index')->with('success', 'Rumah berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan rumah: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal mengunggah rumah: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan formulir untuk mengedit rumah yang ditentukan.
     */
    public function edit(House $house)
    {
        // Pastikan agen memiliki izin untuk mengedit rumah ini
        if ($house->agent_id !== Auth::id()) {
            return redirect()->route('agent.index')->with('error', 'Anda tidak memiliki izin untuk mengedit rumah ini.');
        }
        return view('agent.edit', compact('house'));
    }

    /**
     * Perbarui rumah yang ditentukan dalam penyimpanan.
     */
    public function update(Request $request, House $house)
    {
        // Pastikan agen memiliki izin untuk mengedit rumah ini
        if ($house->agent_id !== Auth::id()) {
            return redirect()->route('agent.index')->with('error', 'Anda tidak memiliki izin untuk mengedit rumah ini.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:Tersedia,Dalam Proses,Terjual',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Foto boleh kosong saat update
        ]);

        try {
            if ($request->hasFile('photo')) {
                // Hapus foto lama jika ada
                if ($house->photo_path && Storage::disk('public')->exists(str_replace('public/', '', $house->photo_path))) {
                    Storage::disk('public')->delete(str_replace('public/', '', $house->photo_path));
                    Log::info('Foto lama dihapus: ' . $house->photo_path);
                }

                $directory = 'houses';
                // Pastikan direktori ada
                if (!Storage::disk('public')->exists($directory)) {
                    Storage::disk('public')->makeDirectory($directory);
                    Log::info('Direktori dibuat: ' . $directory . ' di disk publik');
                }

                $file = $request->file('photo');
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs($directory, $fileName, 'public');

                if (!$path || !Storage::disk('public')->exists($path)) {
                    throw new \Exception('Gagal menyimpan file gambar baru. Path: ' . $path);
                }

                $validated['photo_path'] = 'public/' . $path; // Perbarui path foto baru
                Log::info('Foto baru diunggah: ' . $fileName . ' ke ' . $path);
            }

            $house->update($validated); // Perbarui data rumah
            Log::info('Rumah diperbarui dengan ID: ' . $house->id);

            return redirect()->route('agent.index')->with('success', 'Rumah berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui rumah: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui rumah: ' . $e->getMessage());
        }
    }

    /**
     * Hapus rumah yang ditentukan dari penyimpanan.
     */
    public function destroy(House $house)
    {
        // Pastikan agen memiliki izin untuk menghapus rumah ini
        if ($house->agent_id !== Auth::id()) {
            return redirect()->route('agent.index')->with('error', 'Anda tidak memiliki izin untuk menghapus rumah ini.');
        }

        try {
            // Hapus foto terkait jika ada
            if ($house->photo_path && Storage::disk('public')->exists(str_replace('public/', '', $house->photo_path))) {
                Storage::disk('public')->delete(str_replace('public/', '', $house->photo_path));
                Log::info('Foto dihapus: ' . $house->photo_path);
            }
            $house->delete(); // Hapus entri rumah dari database
            Log::info('Rumah dihapus dengan ID: ' . $house->id);

            return redirect()->route('agent.index')->with('success', 'Rumah berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus rumah: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus rumah: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan daftar penawaran untuk rumah agen.
     */
    public function requests(Request $request)
    {
        $search = $request->query('search');
        $sortBy = $request->query('sort_by', 'created_at'); // Default sort by created_at
        $sortOrder = $request->query('sort_order', 'desc');

        // Validasi kolom pengurutan
        $validSortColumns = ['offer_price', 'created_at', 'status'];
        $sortBy = in_array($sortBy, $validSortColumns) ? $sortBy : 'created_at';
        $sortOrder = in_array($sortOrder, ['asc', 'desc']) ? $sortOrder : 'desc';

        // Ambil penawaran untuk rumah milik agen
        $offers = Offer::whereHas('house', function ($query) {
            $query->where('agent_id', Auth::id());
        })
        ->with(['buyer', 'house']) // Eager load buyer dan house untuk akses data
        ->when($search, function ($query, $search) {
            $query->whereHas('house', function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            });
        })
        ->orderBy($sortBy, $sortOrder)
        ->paginate(10);

        return view('agent.requests', compact('offers', 'search', 'sortBy', 'sortOrder'));
    }

    /**
     * Setujui penawaran dan tandai rumah sebagai terjual.
     */
    public function approveOffer(Offer $offer)
    {
        // Pastikan agen memiliki izin untuk menyetujui penawaran ini
        if ($offer->house->agent_id !== Auth::id()) {
            return redirect()->route('agent.requests')->with('error', 'Anda tidak memiliki izin untuk menyetujui penawaran ini.');
        }

        DB::beginTransaction(); // Mulai transaksi database
        try {
            // Perbarui status penawaran
            $offer->update(['status' => 'Disetujui']);
            // Perbarui status rumah menjadi 'Terjual'
            $offer->house->update(['status' => 'Terjual']);

            // Opsional: Tolak penawaran lain untuk rumah yang sama
            Offer::where('house_id', $offer->house_id)
                ->where('id', '!=', $offer->id)
                ->where('status', '!=' , 'Ditolak') // Jangan menimpa yang sudah ditolak
                ->update(['status' => 'Ditolak']);

            DB::commit(); // Komit transaksi
            Log::info('Penawaran disetujui untuk ID rumah: ' . $offer->house_id);

            return redirect()->route('agent.requests')->with('success', 'Penawaran berhasil disetujui. Rumah telah ditandai sebagai terjual.');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaksi jika terjadi error
            Log::error('Gagal menyetujui penawaran: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menyetujui penawaran: ' . $e->getMessage());
        }
    }

    /**
     * Tolak penawaran.
     */
    public function rejectOffer(Offer $offer)
    {
        // Pastikan agen memiliki izin untuk menolak penawaran ini
        if ($offer->house->agent_id !== Auth::id()) {
            return redirect()->route('agent.requests')->with('error', 'Anda tidak memiliki izin untuk menolak penawaran ini.');
        }

        try {
            $offer->update(['status' => 'Ditolak']);
            Log::info('Penawaran ditolak untuk ID rumah: ' . $offer->house_id);

            return redirect()->route('agent.requests')->with('success', 'Penawaran berhasil ditolak.');
        } catch (\Exception $e) {
            Log::error('Gagal menolak penawaran: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menolak penawaran: ' . $e->getMessage());
        }
    }

    /**
     * Dapatkan percakapan untuk agen (untuk polling AJAX).
     */
    public function getConversations(Request $request)
    {
        $conversations = Conversation::where('agent_id', Auth::id())
            ->with(['buyer', 'house', 'messages' => function ($query) {
                $query->latest()->limit(1);
            }])
            ->withCount(['messages as unread_count' => function ($query) {
                $query->where('is_read', false)
                      ->where('sender_type', 'buyer'); // Hanya hitung pesan dari buyer yang belum dibaca
            }])
            ->orderByDesc('updated_at') // Urutkan berdasarkan aktivitas terbaru
            ->get()
            ->map(function ($conversation) {
                // Return data sebagai array asosiatif
                return [
                    'id' => $conversation->id,
                    'buyer_name' => $conversation->buyer->name ?? 'Pembeli Tidak Dikenal', // Handle jika buyer null
                    'house_title' => $conversation->house->title ?? 'Rumah Tidak Tersedia', // Handle jika house null
                    'unread_count' => $conversation->unread_count,
                    'last_message' => $conversation->messages->first() ? [
                        'message_text' => $conversation->messages->first()->message_text,
                        'created_at' => $conversation->messages->first()->created_at->toDateTimeString(),
                    ] : null,
                ];
            });

        return response()->json(['conversations' => $conversations]);
    }

    /**
     * Dapatkan pesan untuk percakapan tertentu.
     */
    public function getMessages(Request $request, $conversationId)
    {
        $conversation = Conversation::where('id', $conversationId)
            ->where('agent_id', Auth::id())
            ->firstOrFail(); // Ambil atau gagal jika tidak ditemukan/tidak berizin

        $messages = $conversation->messages()
            ->where('id', '>', $request->query('last_message_id', 0)) // Ambil hanya pesan yang lebih baru
            ->with(['buyer', 'agent']) // Eager load sender info
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'sender_id' => $message->sender_id,
                    'sender_type' => $message->sender_type,
                    // Tentukan nama pengirim berdasarkan tipe pengirim
                    'sender_name' => $message->sender_type === 'buyer'
                                    ? ($message->buyer->name ?? 'Pembeli')
                                    : 'Anda',
                    'message_text' => $message->message_text,
                    'created_at' => $message->created_at->toDateTimeString(),
                ];
            });

        // Tandai pesan yang diterima oleh agen dalam percakapan ini sebagai sudah dibaca
        // Ini lebih akurat daripada mereset `unread_count` langsung pada Conversation
        Message::where('conversation_id', $conversation->id)
               ->where('sender_type', 'buyer') // Hanya pesan dari pembeli
               ->where('is_read', false)
               ->update(['is_read' => true]);

        // Setelah itu, kita bisa reset `unread_count` di Conversation, meskipun ini mungkin tidak diperlukan
        // jika Anda menghitungnya secara dinamis di `getConversations` berdasarkan `is_read` di tabel `messages`.
        // Jika Anda menggunakan kolom `unread_count` di tabel `conversations` untuk cache, perbarui di sini.
        $conversation->update(['unread_count' => 0]);


        return response()->json(['messages' => $messages]);
    }

    /**
     * Kirim pesan dalam percakapan.
     */
    public function sendMessage(Request $request, $conversationId)
    {
        $request->validate([
            'message_text' => 'required|string|max:1000',
        ]);

        $conversation = Conversation::where('id', $conversationId)
            ->where('agent_id', Auth::id())
            ->firstOrFail();

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => Auth::id(),
            'sender_type' => 'agent', // Tipe pengirim adalah 'agen'
            'message_text' => $request->input('message_text'),
            'is_read' => true, // Pesan yang dikirim agen otomatis sudah dibaca oleh agen
        ]);

        // Perbarui `updated_at` percakapan agar percakapan tampil di atas
        $conversation->touch();

        // Increment `unread_count` untuk pembeli (ini adalah kolom `unread_count` di tabel conversations)
        // Ini akan memungkinkan pembeli melihat ada pesan baru dari agen.
        // Penting: pastikan logika ini konsisten dengan bagaimana `unread_count` dihitung di sisi pembeli.
        // Jika pembeli memiliki tabel `messages` dan menghitung `unread_count` dari sana, ini mungkin tidak diperlukan.
        // Tetapi jika `unread_count` adalah kolom di tabel `conversations` yang khusus untuk pembeli, maka ini benar.
        // Untuk saat ini, asumsikan ini adalah `unread_count` khusus untuk notifikasi buyer di UI buyer.
        $conversation->increment('buyer_unread_count'); // Asumsi ada kolom `buyer_unread_count` jika notifikasi terpisah

        return response()->json([
            'sent_message' => [
                'id' => $message->id,
                'sender_id' => $message->sender_id,
                'sender_type' => $message->sender_type,
                'sender_name' => 'Anda', // Tampilkan sebagai 'Anda' untuk agen
                'message_text' => $message->message_text,
                'created_at' => $message->created_at->toDateTimeString(),
            ]
        ]);
    }
}