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
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;

class OrderanOnlineController extends Controller
{
    // public function updateData($id, $statusCucian, $statusPembayaran = null)
    // {
    //     try {
    //         if ($statusPembayaran !== null) {
    //             StatusPembayaran::create([
    //                 'orderan_id' => $id,
    //                 'status'     => $statusPembayaran,
    //                 'tgl'        => now(),
    //             ]);
    //         }

    //         StatusCucian::create([
    //             'orderan_id' => $id,
    //             'status'     => $statusCucian,
    //             'tgl'        => now(),
    //         ]);

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Data berhasil update'
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Data gagal update: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function orderLangsung()
    {
        $jenisLaundry = JenisLaundry::all();
        $orderan = Orderan::with([
            'jenisLaundry',
            'orderanOnline',
            'statusCucian' => function ($query) {
                $query->orderBy('tgl', 'asc');
            },
            'statusPembayaran' => function ($query) {
                $query->orderBy('tgl', 'asc');
            }
        ])->whereHas('orderanOnline', function ($query) {
            $query->where('user_id', auth()->user()->id);
        })->where('is_paket', '0')->where('is_offline', '0')->get();
        return view('members.order_langsung.order_langsung', compact('jenisLaundry', 'orderan'));
    }

    public function orderLangsungStore(Request $request)
    {
        try {
            $request->validate([
                'jenis_laundry_id' => 'required',
            ]);

            $orderan = Orderan::create([
                'kode_order'    => substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8) . '_' . time(),
                'jenis_laundry_id'    => $request->jenis_laundry_id,
                'metode_pembayaran'       => 'Transfer',
                'is_offline'       => '0',
                'is_paket'         => '0'
            ]);

            $this->updateData($orderan->id, 'Menunggu Set Lokasi', 'Belum Lunas');

            OrderanOnline::create([
                'orderan_id' => $orderan->id,
                'user_id'   => auth()->user()->id,
            ]);

            return redirect()->back()->with('success', 'Order berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function orderLangsungUpdate(Request $request)
    {
        try {
            $request->validate([
                'jenis_laundry_id' => 'required',
            ]);

            $orderan = Orderan::findOrFail($request->id);
            $orderan->update([
                'jenis_laundry_id' => $request->jenis_laundry_id,
            ]);

            return redirect()->back()->with('success', 'Order berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function orderLangsungDestroy($id)
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

    public function setLocation($id)
    {
        return view('members.order_langsung.set_location', compact('id'));
    }

    public function search(Request $request)
    {
        $query = $request->query('q');
        if (!$query) {
            return response()->json([]);
        }
        $cacheKey = 'geocode:' . md5($query);
        $results = Cache::remember($cacheKey, 600, function () use ($query) {
            try {
                $response = Http::withHeaders([
                    'User-Agent' => 'MyLaravelApp/1.0 (a68laundry@gmail.com)',
                ])->get('https://nominatim.openstreetmap.org/search', [
                    'format' => 'json',
                    'q' => $query,
                    'addressdetails' => 1,
                    'limit' => 5,
                ]);
                if ($response->successful()) {
                    return $response->json();
                }
                return [];
            } catch (\Exception $e) {
                Log::error('Geocoding error: ' . $e->getMessage());
                return [];
            }
        });
        return response()->json($results);
    }

    public function updateLocation(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required',
                'latitudeInput' => 'required',
                'longitudeInput' => 'required',
                'distanceInput' => 'required',
            ]);

            OrderanOnline::where('orderan_id', $request->id)->update([
                'latitude' => $request->latitudeInput,
                'longitude' => $request->longitudeInput,
                'jarak' => $request->distanceInput,
                'ongkir' => $request->distanceInput * 5000,
            ]);

            $this->updateData(
                $request->id,
                'Menunggu Cucian Diambil',
                null
            );

            return redirect('/orderLangsung')->with('success', 'Lokasi berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect('/orderLangsung')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function bayarOrderan(Request $request)
    {
        try {
            $orderan = Orderan::with('orderanOnline')->findOrFail($request->id);
            $totalPembayaran = $orderan->harga + $orderan->orderanOnline->ongkir;

            Config::$serverKey = config('midtrans.serverKey');
            Config::$isProduction = false;
            Config::$isSanitized = true;
            Config::$is3ds = true;

            $params = array(
                'transaction_details' => array(
                    'order_id' => rand(),
                    'gross_amount' => $totalPembayaran
                ),
                'customer_details' => array(
                    'first_name' => auth()->user()->nama,
                    'phone'      => auth()->user()->no_hp,
                    'email'      => auth()->user()->email,
                    'address'    => auth()->user()->alamat,
                )
            );

            $snapToken = Snap::getSnapToken($params);

            $orderan->update([
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

    public function bayarOrderanSuccess($id)
    {
        try {
            $orderan = Orderan::where('snap_token', $id)->first();

            $this->updateData(
                $orderan->id,
                'Sedang Dicuci',
                'Lunas'
            );

            return redirect('/orderLangsung')->with('success', 'Pembayaran berhasil dilakukan.');
        } catch (\Exception $e) {
            return redirect('/orderLangsung')->with('error', 'Terjadi kesalahan saat memproses pembayaran: ' . $e->getMessage());
        }
    }

    public function orderLangsungSelesai($id)
    {
        return $this->updateData($id, 'Order Selesai', null);
    }

    public function orderLangsungCetakNota($id)
    {
        try {
            $orderan = Orderan::with([
                'orderanOnline',
                'jenisLaundry',
                'statusCucian' => function ($query) {
                    $query->orderBy('tgl', 'asc');
                },
                'statusPembayaran' => function ($query) {
                    $query->orderBy('tgl', 'asc');
                }
            ])->where('id', $id)->first();

            return view('members.order_langsung.cetak_nota', compact('orderan'));
        } catch (\Exception $e) {
            return redirect('/orderLangsung')->with('error', 'Terjadi kesalahan saat mencetak nota: ' . $e->getMessage());
        }
    }

    // =================================================================================================
    // =================================================================================================

    public function orderPaket()
    {
        $jenisLaundry = JenisLaundry::all();
        $orderan = Orderan::with([
            'jenisLaundry',
            'orderanOnline',
            'statusCucian' => function ($query) {
                $query->orderBy('tgl', 'asc');
            },
            'statusPembayaran' => function ($query) {
                $query->orderBy('tgl', 'asc');
            }
        ])->whereHas('orderanOnline', function ($query) {
            $query->where('user_id', auth()->user()->id);
        })->where('is_paket', '1')->where('is_offline', '0')->get();
        $paketLaundry = PaketMember::where('user_id', auth()->user()->id)->with('paketLaundry.jenisLaundry')->get();

        return view('members.order_paket.order_paket', compact('jenisLaundry', 'orderan', 'paketLaundry'));
    }

    public function orderPaketStore(Request $request)
    {
        try {
            $request->validate([
                'jenis_laundry_id' => 'required',
            ]);

            $orderan = Orderan::create([
                'kode_order'    => substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8) . '_' . time(),
                'jenis_laundry_id'    => $request->jenis_laundry_id,
                'metode_pembayaran'       => 'Transfer',
                'is_offline'       => '0',
                'is_paket'         => '1'
            ]);

            $this->updateData($orderan->id, 'Menunggu Set Lokasi', 'Lunas');

            OrderanOnline::create([
                'orderan_id' => $orderan->id,
                'user_id'   => auth()->user()->id,
            ]);

            return redirect()->back()->with('success', 'Order berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function orderPaketUpdate(Request $request)
    {
        try {
            $request->validate([
                'jenis_laundry_id' => 'required',
            ]);

            $orderan = Orderan::findOrFail($request->id);
            $orderan->update([
                'jenis_laundry_id' => $request->jenis_laundry_id,
            ]);

            return redirect()->back()->with('success', 'Order berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function orderPaketDestroy($id)
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

    public function setLocationPaket($id)
    {
        return view('members.order_paket.set_location', compact('id'));
    }

    public function searchPaket(Request $request)
    {
        $query = $request->query('q');
        if (!$query) {
            return response()->json([]);
        }
        $cacheKey = 'geocode:' . md5($query);
        $results = Cache::remember($cacheKey, 600, function () use ($query) {
            try {
                $response = Http::withHeaders([
                    'User-Agent' => 'MyLaravelApp/1.0 (a68laundry@gmail.com)',
                ])->get('https://nominatim.openstreetmap.org/search', [
                    'format' => 'json',
                    'q' => $query,
                    'addressdetails' => 1,
                    'limit' => 5,
                ]);
                if ($response->successful()) {
                    return $response->json();
                }
                return [];
            } catch (\Exception $e) {
                Log::error('Geocoding error: ' . $e->getMessage());
                return [];
            }
        });
        return response()->json($results);
    }

    public function updateLocationPaket(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required',
                'latitudeInput' => 'required',
                'longitudeInput' => 'required',
                'distanceInput' => 'required',
            ]);

            OrderanOnline::where('orderan_id', $request->id)->update([
                'latitude' => $request->latitudeInput,
                'longitude' => $request->longitudeInput,
                'jarak' => $request->distanceInput,
                'ongkir' => $request->distanceInput * 5000,
            ]);

            $this->updateData(
                $request->id,
                'Menunggu Cucian Diambil',
                null
            );

            return redirect('/orderPaket')->with('success', 'Lokasi berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect('/orderPaket')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function orderPaketSelesai($id)
    {
        return $this->updateData($id, 'Order Selesai', null);
    }

    public function orderPaketCetakNota($id)
    {
        try {
            $orderan = Orderan::with([
                'orderanOnline',
                'jenisLaundry',
                'statusCucian' => function ($query) {
                    $query->orderBy('tgl', 'asc');
                },
                'statusPembayaran' => function ($query) {
                    $query->orderBy('tgl', 'asc');
                }
            ])->where('id', $id)->first();

            return view('members.order_paket.cetak_nota', compact('orderan'));
        } catch (\Exception $e) {
            return redirect('/orderPaket')->with('error', 'Terjadi kesalahan saat mencetak nota: ' . $e->getMessage());
        }
    }
}
