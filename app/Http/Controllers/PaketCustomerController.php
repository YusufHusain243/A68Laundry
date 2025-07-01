<?php

namespace App\Http\Controllers;

use App\Models\PaketMember;
use App\Models\PaketLaundry;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;

class PaketCustomerController extends Controller
{
    public function index()
    {
        $paketMember = PaketMember::with('paketLaundry.jenisLaundry')->where('user_id', auth()->user()->id)->get();
        $paketLaundry = PaketLaundry::with('jenisLaundry')->get();
        return view('members.paket.paket', compact('paketMember', 'paketLaundry'));
    }

    public function store($id)
    {
        try {
            $paketLaundry = PaketLaundry::findOrFail($id);

            $paketMember = PaketMember::create([
                'paket_laundry_id' => $id,
                'user_id' => auth()->user()->id,
                'kg_terpakai' => 0,
                'kg_sisa' => $paketLaundry->berat,
                'status_pembayaran' => 'Belum Lunas',
            ]);

            
            Config::$serverKey = config('midtrans.serverKey');
            Config::$isProduction = false;
            Config::$isSanitized = true;
            Config::$is3ds = true;
            
            $params = array(
                'transaction_details' => array(
                    'order_id' => rand(),
                    'gross_amount' => $paketLaundry->harga,
                ),
                'customer_details' => array(
                    'first_name' => auth()->user()->nama,
                    'phone'      => auth()->user()->no_hp,
                    'email'      => auth()->user()->email,
                    'address'    => auth()->user()->alamat,
                )
            );

            $snapToken = Snap::getSnapToken($params);

            $paketMember->update([
                'snap_token' => $snapToken,
            ]);

            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat melakukan pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $paketMember = PaketMember::findOrFail($id);
            $paketMember->delete();
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data gagal dihapus: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request)
    {
        $validated =  $request->validate([
            'paket_laundry_id' => 'required',
        ]);

        try {
            PaketMember::where('id', $request->id)->update($validated);
            return redirect('/paketLaundryMember')->with('success', 'Data berhasil diubah');
        } catch (\Exception $e) {
            return redirect('/paketLaundryMember')->with('error', 'Data gagal diubah: ' . $e->getMessage());
        }
    }

    public function bayarSuccess($id)
    {
        try {
            $paketMember = PaketMember::where('snap_token', $id)->firstOrFail();
            $paketMember->update([
                'status_pembayaran' => 'Lunas',
            ]);
            return redirect('/paketLaundryMember')->with('success', 'Pembayaran berhasil');
        } catch (\Exception $e) {
            return redirect('/paketLaundryMember')->with('error', 'Pembayaran gagal: ' . $e->getMessage());
        }
    }
}
