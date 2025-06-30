<?php

namespace App\Http\Controllers;

use App\Models\Orderan;
use App\Models\JenisLaundry;
use App\Models\OrderanOffline;
use App\Models\PaketMember;
use App\Models\StatusCucian;
use App\Models\StatusPembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Midtrans\Config;
use Midtrans\Snap;

class OrderanOfflineController extends Controller
{
    public function orderanOffline()
    {
        $jenisLaundry = JenisLaundry::all();
        $orderan = Orderan::with([
            'orderanOffline',
            'jenisLaundry',
            'statusCucian' => function ($query) {
                $query->orderBy('tgl', 'asc');
            },
            'statusPembayaran' => function ($query) {
                $query->orderBy('tgl', 'asc');
            }
        ])->where('is_offline', '1')->get();
        return view('staffs.offline.offline', compact('jenisLaundry', 'orderan'));
    }

    public function orderanOfflineStore(Request $request)
    {
        $validatedData = $request->validate([
            'nama'              => 'required',
            'no_hp'             => 'required',
            'email'             => 'required',
            'alamat'            => 'required',
            'jenis_laundry_id'  => 'required',
            'berat'             => 'required',
            'harga'             => 'required',
            'metode_pembayaran' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $orderan = Orderan::create([
                'kode_order'        => substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8) . '_' . time(),
                'jenis_laundry_id'  => $validatedData['jenis_laundry_id'],
                'berat'             => $validatedData['berat'],
                'harga'             => $validatedData['harga'],
                'metode_pembayaran' => $validatedData['metode_pembayaran'],
                'is_offline'        => '1',
                'is_paket'          => '0',
                'status'            => '0',
            ]);

            OrderanOffline::create([
                'orderan_id' => $orderan->id,
                'nama'       => $validatedData['nama'],
                'no_hp'      => $validatedData['no_hp'],
                'email'      => $validatedData['email'],
                'alamat'     => $validatedData['alamat'],
            ]);

            StatusCucian::create([
                'orderan_id' => $orderan->id,
                'status'     => 'Orderan Masuk',
                'tgl'        => now(),
            ]);

            StatusPembayaran::create([
                'orderan_id' => $orderan->id,
                'status'     => 'Belum Lunas',
                'tgl'        => now(),
            ]);

            if ($validatedData['metode_pembayaran'] == 'Transfer') {
                Config::$serverKey = config('midtrans.serverKey');
                Config::$isProduction = false;
                Config::$isSanitized = true;
                Config::$is3ds = true;

                $params = array(
                    'transaction_details' => array(
                        'order_id' => rand(),
                        'gross_amount' => $validatedData['harga'],
                    ),
                    'customer_details' => array(
                        'first_name' => $validatedData['nama'],
                        'phone'      => $validatedData['no_hp'],
                        'email'      => $validatedData['email'],
                        'address'    => $validatedData['alamat'],
                    )
                );

                $snapToken = Snap::getSnapToken($params);

                $orderan->update([
                    'snap_token' => $snapToken,
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Orderan offline berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambahkan orderan offline: ' . $e->getMessage());
        }
    }

    public function orderanOfflineDestroy($id)
    {
        try {
            $orderan = Orderan::findOrFail($id);
            $orderan->delete();
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

    public function orderanOfflineUpdate(Request $request)
    {
        $validatedData = $request->validate([
            'id'                    => 'required',
            'nama'                  => 'required',
            'no_hp'                 => 'required',
            'email'                 => 'required',
            'alamat'                => 'required',
            'jenis_laundry_id'      => 'required',
            'berat'                 => 'required',
            'harga'                 => 'required',
            'metode_pembayaran'     => 'required',
        ]);

        try {
            DB::beginTransaction();

            $orderan = Orderan::findOrFail($request->id);
            $orderan->update([
                'jenis_laundry_id' => $validatedData['jenis_laundry_id'],
                'berat'                 => $validatedData['berat'],
                'harga'                 => $validatedData['harga'],
                'metode_pembayaran'      => $validatedData['metode_pembayaran'],
            ]);

            OrderanOffline::where('orderan_id', $request->id)->update([
                'nama'       => $validatedData['nama'],
                'no_hp'      => $validatedData['no_hp'],
                'email'      => $validatedData['email'],
                'alamat'     => $validatedData['alamat'],
            ]);

            if ($validatedData['metode_pembayaran'] == 'Transfer') {
                Config::$serverKey = config('midtrans.serverKey');
                Config::$isProduction = false;
                Config::$isSanitized = true;
                Config::$is3ds = true;

                $params = array(
                    'transaction_details' => array(
                        'order_id' => rand(),
                        'gross_amount' => $validatedData['harga'],
                    ),
                    'customer_details' => array(
                        'first_name' => $validatedData['nama'],
                        'phone'      => $validatedData['no_hp'],
                        'email'      => $validatedData['email'],
                        'address'    => $validatedData['alamat'],
                    )
                );

                $snapToken = Snap::getSnapToken($params);

                $orderan->update([
                    'snap_token' => $snapToken,
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Orderan offline berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui orderan offline: ' . $e->getMessage());
        }
    }

    public function orderanOfflineBayarCash($id)
    {
        return $this->updateData($id, 'Sedang Dicuci', 'Lunas');
    }

    public function orderanOfflineCucianSelesai($id)
    {
        $this->sendWa(
            OrderanOffline::where('orderan_id', $id)->first()->no_hp,
            "Cucian Anda sudah selesai. Silakan ambil cucian Anda di tempat kami."
        );
        return $this->updateData($id, 'Cucian Selesai', null);
    }

    public function orderanOfflineCucianDiambil($id)
    {
        Orderan::where('id', $id)->update(['status' => '1']);
        return $this->updateData($id, 'Cucian Diambil', null);
    }

    public function orderanOfflineBayarSuccess($id)
    {
        try {
            $orderan = Orderan::where('snap_token', $id)->first();

            $this->updateData($orderan->id, 'Sedang Dicuci', 'Lunas');

            return redirect('/orderanOffline')->with('success', 'Pembayaran berhasil dilakukan.');
        } catch (\Exception $e) {
            return redirect('/orderanOffline')->with('error', 'Terjadi kesalahan saat memproses pembayaran: ' . $e->getMessage());
        }
    }

    public function orderanOfflineCetakNota($id)
    {
        try {
            $orderan = Orderan::with([
                'orderanOffline',
                'jenisLaundry',
                'statusCucian' => function ($query) {
                    $query->orderBy('tgl', 'asc');
                },
                'statusPembayaran' => function ($query) {
                    $query->orderBy('tgl', 'asc');
                }
            ])->where('id', $id)->first();

            return view('staffs.offline.cetak_nota', compact('orderan'));
        } catch (\Exception $e) {
            return redirect('/orderanOffline')->with('error', 'Terjadi kesalahan saat mencetak nota: ' . $e->getMessage());
        }
    }
}
