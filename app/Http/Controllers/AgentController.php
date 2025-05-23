<?php

namespace App\Http\Controllers;

use App\Models\House;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AgentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $sortBy = $request->query('sort_by', 'price');
        $sortOrder = $request->query('sort_order', 'asc');

        $houses = House::where('agent_id', Auth::id())
            ->when($search, function ($query, $search) {
                return $query->where('title', 'like', "%{$search}%");
            })
            ->orderBy($sortBy, $sortOrder)
            ->paginate(10);

        return view('agent.index', compact('houses', 'search', 'sortBy', 'sortOrder'));
    }

    public function create()
    {
        return view('agent.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            $directory = 'houses';
            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
                Log::info('Created directory: ' . $directory . ' on public disk');
            }

            $file = $request->file('photo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs($directory, $fileName, 'public');

            if (!$path || !Storage::disk('public')->exists($path)) {
                throw new \Exception('Gagal menyimpan file gambar. Path: ' . $path);
            }

            Log::info('File uploaded: ' . $fileName . ' to ' . $path);
            Log::info('File exists: ' . (Storage::disk('public')->exists($path) ? 'Yes' : 'No'));

            $house = House::create([
                'agent_id' => Auth::id(),
                'title' => $validated['title'],
                'price' => $validated['price'],
                'photo_path' => 'public/' . $path,
                'status' => 'Tersedia',
            ]);

            Log::info('House created with ID: ' . $house->id . ' and photo_path: ' . $house->photo_path);

            return redirect()->route('agent.index')->with('success', 'Rumah berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Failed to store house: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengunggah rumah: ' . $e->getMessage());
        }
    }

    public function edit(House $house)
    {
        if ($house->agent_id !== Auth::id()) {
            return redirect()->route('agent.index')->with('error', 'Anda tidak memiliki izin untuk mengedit rumah ini.');
        }
        return view('agent.edit', compact('house'));
    }

    public function update(Request $request, House $house)
    {
        if ($house->agent_id !== Auth::id()) {
            return redirect()->route('agent.index')->with('error', 'Anda tidak memiliki izin untuk mengedit rumah ini.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:Tersedia,Dalam Proses,Terjual',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            if ($request->hasFile('photo')) {
                if ($house->photo_path && Storage::disk('public')->exists(str_replace('public/', '', $house->photo_path))) {
                    Storage::disk('public')->delete(str_replace('public/', '', $house->photo_path));
                }

                $directory = 'houses';
                if (!Storage::disk('public')->exists($directory)) {
                    Storage::disk('public')->makeDirectory($directory);
                    Log::info('Created directory: ' . $directory . ' on public disk');
                }

                $file = $request->file('photo');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs($directory, $fileName, 'public');

                if (!$path || !Storage::disk('public')->exists($path)) {
                    throw new \Exception('Gagal menyimpan file gambar baru. Path: ' . $path);
                }

                $validated['photo_path'] = 'public/' . $path;
                Log::info('New photo uploaded: ' . $fileName . ' to ' . $path);
            }

            $house->update($validated);
            Log::info('House updated with ID: ' . $house->id);

            return redirect()->route('agent.index')->with('success', 'Rumah berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Failed to update house: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui rumah: ' . $e->getMessage());
        }
    }

    public function destroy(House $house)
    {
        if ($house->agent_id !== Auth::id()) {
            return redirect()->route('agent.index')->with('error', 'Anda tidak memiliki izin untuk menghapus rumah ini.');
        }

        try {
            if ($house->photo_path && Storage::disk('public')->exists(str_replace('public/', '', $house->photo_path))) {
                Storage::disk('public')->delete(str_replace('public/', '', $house->photo_path));
                Log::info('Photo deleted: ' . $house->photo_path);
            }
            $house->delete();
            Log::info('House deleted with ID: ' . $house->id);

            return redirect()->route('agent.index')->with('success', 'Rumah berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Failed to delete house: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus rumah: ' . $e->getMessage());
        }
    }

    public function requests(Request $request)
    {
        $search = $request->query('search');
        $sortBy = $request->query('sort_by', 'offer_price');
        $sortOrder = $request->query('sort_order', 'asc');

        $offers = Offer::whereHas('house', function ($query) {
            $query->where('agent_id', Auth::id());
        })
        ->when($search, function ($query, $search) {
            $query->whereHas('house', function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            });
        })
        ->orderBy($sortBy, $sortOrder)
        ->paginate(10);

        return view('agent.requests', compact('offers', 'search', 'sortBy', 'sortOrder'));
    }

    public function approveOffer(Offer $offer)
    {
        if ($offer->house->agent_id !== Auth::id()) {
            return redirect()->route('agent.requests')->with('error', 'Anda tidak memiliki izin untuk menyetujui penawaran ini.');
        }

        try {
            $offer->update(['status' => 'Disetujui']);
            $offer->house->update(['status' => 'Terjual']);
            Log::info('Offer approved for house ID: ' . $offer->house_id);

            return redirect()->route('agent.requests')->with('success', 'Penawaran disetujui.');
        } catch (\Exception $e) {
            Log::error('Failed to approve offer: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menyetujui penawaran: ' . $e->getMessage());
        }
    }

    public function rejectOffer(Offer $offer)
    {
        if ($offer->house->agent_id !== Auth::id()) {
            return redirect()->route('agent.requests')->with('error', 'Anda tidak memiliki izin untuk menolak penawaran ini.');
        }

        try {
            $offer->update(['status' => 'Ditolak']);
            Log::info('Offer rejected for house ID: ' . $offer->house_id);

            return redirect()->route('agent.requests')->with('success', 'Penawaran ditolak.');
        } catch (\Exception $e) {
            Log::error('Failed to reject offer: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menolak penawaran: ' . $e->getMessage());
        }
    }
}