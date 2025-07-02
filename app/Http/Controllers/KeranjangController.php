<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use App\Models\Orderan;
use App\Models\OrderanOnline;
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

        if ($metodePembayaran == 'Transfer') {
            return $this->paymentTransfer($keranjangIds, $metodePembayaran);
        }else{
            return $this->paymentPaket($keranjangIds, $metodePembayaran);
        }
    }

    private function paymentTransfer($keranjangIds, $metodePembayaran)
    {
        try {
            $keranjangLaundry = Keranjang::whereIn('id', $keranjangIds)
                ->where('user_id', auth()->user()->id)
                ->get();

            foreach ($keranjangLaundry as $jenisLaundry) {
                dd($jenisLaundry->jenisLaundry->nama);
                $orderan = Orderan::create([
                    'jenis_laundry_id'      => $jenisLaundry->jenisLaundry->id,
                    'kode_order'            => substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8) . '_' . time(),
                    'berat'                 => $jenisLaundry->jenisLaundry->berat,
                    'harga'                 => $jenisLaundry->jenisLaundry->harga,
                    'metode_pembayaran'     => 'Transfer',
                    'is_offline'            => '0',
                    'is_paket'              => '0',
                    'status'                => '0',
                ]);
    
                $this->updateData($orderan->id, 'Menunggu Set Lokasi', 'Belum Lunas');
    
                OrderanOnline::create([
                    'orderan_id' => $orderan->id,
                    'user_id'   => auth()->user()->id,
                ]);
            }

            return redirect()->back()->with('success', 'Order berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function paymentPaket($keranjangIds, $metodePembayaran)
    {
        // Implementasi logika pembayaran paket
        // Misalnya, mengupdate status keranjang menjadi '2' (paket dibeli)
        Keranjang::whereIn('id', $keranjangIds)->update(['status' => '2']);
        return redirect()->back()->with('success', 'Paket berhasil dibeli.');
    }
}
