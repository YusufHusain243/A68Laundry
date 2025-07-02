<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use App\Models\PaketMember;
use App\Models\PaketLaundry;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;

class PaketCustomerController extends Controller
{
    public function index()
    {
        $jumlahKeranjang = Keranjang::where('user_id', auth()->user()->id)
                ->where('status', '0')
                ->count();
        $paketMember = PaketMember::with('paketLaundry.jenisLaundry')->where('user_id', auth()->user()->id)->get();
        return view('customers.paketSaya', compact('paketMember', 'jumlahKeranjang'));
    }

    public function store(Request $request)
    {
        try {
            $paketLaundry = PaketLaundry::findOrFail($request->input('id'));

            $paketMember = PaketMember::create([
                'paket_laundry_id' => $request->input('id'),
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

    public function paymentSuccess($snap)
    {
        try {
            $paketMember = PaketMember::where('snap_token', $snap)->firstOrFail();
            $paketMember->update([
            'status_pembayaran' => 'Lunas',
            ]);
            return redirect()->back()->with('success', 'Pembayaran berhasil');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Pembayaran gagal: ' . $e->getMessage());
        }
    }
}
