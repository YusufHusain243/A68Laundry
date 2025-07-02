<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use Illuminate\Http\Request;

class KeranjangController extends Controller
{
    public function index()
    {
        try {
            $keranjangs = Keranjang::where('user_id', auth()->user()->id)
                ->where('status', '0')
                ->get();

            $jumlahKeranjang = Keranjang::where('user_id', auth()->user()->id)
                ->where('status', '0')
                ->count();
            return view('customers.keranjang', compact('keranjangs', 'jumlahKeranjang'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to retrieve cart items.']);
        }
    }

    public function store($id)
    {
        try {
            // Check if the entry already exists
            $exists = Keranjang::where('jenis_laundry_id', $id)
                ->where('user_id', auth()->user()->id)
                ->exists();

            if (!$exists) {
                Keranjang::create([
                    'jenis_laundry_id' => $id,
                    'user_id' => auth()->user()->id,
                    'status' => '0',
                ]);
            }
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        try {
            $keranjang = Keranjang::findOrFail($id);
            $keranjang->delete();
            return redirect()->back()->with('success', 'Item removed from cart successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to remove item from cart.']);
        }
    }

    public function checkout(Request $request)
    {
        $keranjangIds = $request->keranjang_ids;
        $metodePembayaran = $request->metode_pembayaran; 

        return $this->paymentTransfer($keranjangIds, $metodePembayaran);
    }

    private function paymentTransfer($keranjangIds, $metodePembayaran)
    {
        $jenisLaundry = Keranjang::whereIn('id', $keranjangIds)
            ->with('jenisLaundry')
            ->get();
            dd($jenisLaundry[0]->jenisLaundry);
    }

    private function paymentPaket($keranjangIds, $metodePembayaran)
    {
        // Implementasi logika pembayaran paket
        // Misalnya, mengupdate status keranjang menjadi '2' (paket dibeli)
        Keranjang::whereIn('id', $keranjangIds)->update(['status' => '2']);
        return redirect()->back()->with('success', 'Paket berhasil dibeli.');
    }
}
