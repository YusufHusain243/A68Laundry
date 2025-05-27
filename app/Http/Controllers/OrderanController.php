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


class OrderanController extends Controller
{
    private function updateData($id, $statusCucian, $statusPembayaran = null)
    {
        try {
            if ($statusPembayaran !== null) {
                StatusPembayaran::create([
                    'orderan_id' => $id,
                    'status'     => $statusPembayaran,
                    'tgl'        => now(),
                ]);
            }

            StatusCucian::create([
                'orderan_id' => $id,
                'status'     => $statusCucian,
                'tgl'        => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil update'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data gagal update: ' . $e->getMessage()
            ], 500);
        }
    }

    private function sendWa($target, $message)
    {
        $response = Http::withHeaders([
            'Authorization' => 'WpXrvWiw7xZM2bHJwcuy',
        ])->post('https://api.fonnte.com/send', [
            'target' => $target,
            'message' => $message,
        ]);

        return $response->body();
    }

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

    public function orderanOfflineCetakNota($id){
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

    // =========================================================================================
    // =========================================================================================

    public function orderanOnline()
    {
        $jenisLaundry = JenisLaundry::all();
        $orderan = Orderan::with([
            'orderanOnline.user',
            'jenisLaundry',
            'statusCucian' => function ($query) {
                $query->orderBy('tgl', 'asc');
            },
            'statusPembayaran' => function ($query) {
                $query->orderBy('tgl', 'asc');
            }
        ])->where('is_offline', '0')->get();
        return view('staffs.online.online', compact('orderan', 'jenisLaundry'));
    }

    public function orderanOnlineAmbilCucian($id)
    {
        return $this->updateData($id, 'Cucian Diambil', null);
    }

    public function orderanOnlineInputTimbangan(Request $request)
    {
        try {
            DB::beginTransaction();

            $orderan = Orderan::findOrFail($request->id);
            if ($orderan->is_paket == 0) {
                $this->updateData($request->id, 'Menimbang Cucian', 'Menunggu Pembayaran');
                $orderan->update([
                    'berat' => $request->berat,
                    'harga' => $request->harga
                ]);
            } else {
                $paketMember = PaketMember::with('paketLaundry')->whereHas('paketLaundry', function ($query) use ($orderan) {
                    $query->where('jenis_laundry_id', $orderan->jenis_laundry_id);
                })->where('user_id', $orderan->orderanOnline->user_id);

                $total_kg_sisa = $paketMember->sum('kg_sisa');
                $paketMember = $paketMember->get();

                if ($total_kg_sisa > 0 && $request->berat <= $total_kg_sisa) {

                    $this->updateData($request->id, 'Menimbang Cucian', null);
                    $this->updateData($request->id, 'Sedang Dicuci', null);

                    $orderan->update([
                        'berat' => $request->berat,
                        'harga' => $request->harga
                    ]);
                    $berat = $request->berat;
                    foreach ($paketMember as $pm) {
                        if ($pm->kg_sisa > 0) {
                            if ($berat > $pm->kg_sisa) {
                                $terpakai = $pm->kg_terpakai + $pm->kg_sisa;
                                $sisa = 0;
                                $berat = $berat - $pm->kg_sisa;
                                PaketMember::where('id', $pm->id)->update([
                                    'kg_terpakai' => $terpakai,
                                    'kg_sisa' => $sisa
                                ]);
                            } else {
                                $terpakai = $pm->kg_terpakai + $berat;
                                $sisa = $pm->kg_sisa - $berat;
                                PaketMember::where('id', $pm->id)->update([
                                    'kg_terpakai' => $terpakai,
                                    'kg_sisa' => $sisa
                                ]);
                                break;
                            }
                        } else {
                            continue;
                        }
                    }
                } else {
                    return redirect('/orderanOnline')->with('error', 'Paket Laundry Anda Sudah Habis');
                }
            }

            DB::commit();

            return redirect('/orderanOnline')->with('success', 'Input Timbangan Berhasil');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect('/orderanOnline')->with('error', 'Terjadi kesalahan saat memproses data.' . $e->getMessage());
        }
    }

    public function orderanOnlineCuciSelesai($id)
    {
        $this->sendWa(
            Orderan::findOrFail($id)->orderanOnline->user->no_hp,
            "Cucian Anda sudah selesai. Silakan ambil cucian Anda di tempat kami."
        );
        return $this->updateData($id, 'Cucian Selesai', null);
    }

    public function orderanOnlineAntarCucian($id)
    {
        return $this->updateData($id, 'Cucian Diantar', null);
    }
}
