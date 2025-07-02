<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use App\Models\Orderan;
use App\Models\OrderanOnline;
use App\Models\PaketMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KeranjangController extends Controller
{
    public function index()
    {
        try {
            $keranjangs = Keranjang::where('user_id', Auth::user()->id)
                ->where('status', '0')
                ->get();

            $jumlahKeranjang = Keranjang::where('user_id', Auth::user()->id)
                ->where('status', '0')
                ->count();

            $paketCustomer = PaketMember::where('user_id', Auth::user()->id)
                ->where('status_pembayaran', 'Lunas')
                ->where('kg_sisa', '>', '0')
                ->get();
            return view('customers.keranjang', compact('keranjangs', 'jumlahKeranjang', 'paketCustomer'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to retrieve cart items.']);
        }
    }

    public function store($id)
    {
        try {
            // Check if the entry already exists
            $exists = Keranjang::where('jenis_laundry_id', $id)
                ->where('user_id', Auth::user()->id)
                ->exists();

            if (!$exists) {
                Keranjang::create([
                    'jenis_laundry_id' => $id,
                    'user_id' => Auth::user()->id,
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
        $paketId = $request->paket_id;

        if ($metodePembayaran == 'Transfer') {
            return $this->paymentTransfer($keranjangIds, $metodePembayaran);
        } else {
            return $this->paymentPaket($keranjangIds, $metodePembayaran, $paketId);
        }
    }

    private function paymentTransfer($keranjangIds, $metodePembayaran)
    {
        try {
            $keranjangLaundry = Keranjang::whereIn('id', $keranjangIds)
                ->where('user_id', Auth::user()->id)
                ->get();

            foreach ($keranjangLaundry as $jenisLaundry) {
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

                OrderanOnline::create([
                    'orderan_id' => $orderan->id,
                    'user_id'   => Auth::user()->id,
                ]);

                Keranjang::where('id', $jenisLaundry->id)->update(['status' => '1']);
            }

            return redirect()->back()->with('success', 'Order berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function paymentPaket($keranjangIds, $metodePembayaran, $paketId)
    {
        try {
            DB::beginTransaction();

            $keranjangLaundry = Keranjang::whereIn('id', $keranjangIds)
                ->where('user_id', Auth::user()->id)
                ->get();

            $jenisPaket = PaketMember::where('id', $paketId)
                ->where('user_id', Auth::user()->id)
                ->first();

            foreach ($keranjangLaundry as $jenisLaundry) {
                if ($jenisPaket->paketLaundry->jenislaundry->nama != $jenisLaundry->jenisLaundry->nama) {
                    throw new \Exception('Paket yang dipilih tidak sesuai dengan jenis laundry yang ada di keranjang.');
                }

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

                OrderanOnline::create([
                    'orderan_id' => $orderan->id,
                    'user_id'   => Auth::user()->id,
                ]);

                Keranjang::where('id', $jenisLaundry->id)->update(['status' => '1']);
                PaketMember::where('id', $paketId)
                    ->where('user_id', Auth::user()->id)
                    ->decrement('kg_sisa', $jenisLaundry->jenisLaundry->berat);

                PaketMember::where('id', $paketId)
                    ->where('user_id', Auth::user()->id)
                    ->increment('kg_terpakai', $jenisLaundry->jenisLaundry->berat);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Order berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
