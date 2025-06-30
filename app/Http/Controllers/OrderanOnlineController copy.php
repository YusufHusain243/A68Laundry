<?php

namespace App\Http\Controllers;

use App\Models\JenisLaundry;
use App\Models\Orderan;
use App\Models\OrderanOnline;
use App\Models\PaketMember;
use App\Models\StatusCucian;
use App\Models\StatusPembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;

class OrderanOnlineControllerC extends Controller
{
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
        Orderan::where('id', $id)->update(['status' => '1']);
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
