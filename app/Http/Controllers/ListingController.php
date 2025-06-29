<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    /**
     * Hapus listing berdasarkan ID.
     */
    public function destroy($id)
    {
        // Cari listing berdasarkan ID
        $listings = Listing::find($id);

        // Jika tidak ditemukan, kembalikan error
        if (!$listings) {
            return redirect()->back()->with('error', 'Listing tidak ditemukan.');
        }

        // Hapus listing
        $listings->delete();

        // Redirect kembali dengan pesan sukses
        return redirect()->route('agent.listings.index')->with('success', 'Listing berhasil dihapus.');
    }
}
