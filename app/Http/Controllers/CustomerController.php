<?php

namespace App\Http\Controllers;

use App\Models\JenisLaundry;
use App\Models\Keranjang;
use App\Models\Orderan;
use App\Models\OrderanOnline;
use App\Models\PaketLaundry;
use App\Models\PaketMember;
use App\Models\StatusCucian;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;

class CustomerController extends Controller
{
    public function index()
    {
        $laundry = JenisLaundry::latest()->take(3)->get();
        $paket = PaketLaundry::latest()->take(3)->get();
        $jumlahKeranjang = Auth::check() ? Keranjang::where('user_id', Auth::user()->id)
            ->where('status', '0')
            ->count() : 0;
        return view('customers.main', compact('laundry', 'paket', 'jumlahKeranjang'));
    }

    public function profileCustomer()
    {
        $jumlahKeranjang = Auth::check() ? Keranjang::where('user_id', Auth::user()->id)
            ->where('status', '0')
            ->count() : 0;
        $profile = User::where('id', Auth::user()->id)->first();
        return view('customers.profile', compact('profile', 'jumlahKeranjang'));
    }

    public function updateProfile(Request $request)
    {
        try {
            $request->validate([
                'nama' => 'required|string|max:255',
                'no_hp' => 'required|string|max:15',
                'email' => 'required|string|email|max:255',
                'alamat' => 'required|string|max:255',
                'username' => 'required|string|max:255',
            ]);

            $profile = User::where('id', Auth::user()->id)->first();

            $dataToUpdate = [
                'nama' => $request->nama,
                'no_hp' => $request->no_hp,
                'email' => $request->email,
                'alamat' => $request->alamat,
                'username' => $request->username,
                'role' => 'Member',
            ];

            // Check if password is provided and add to update array
            if ($request->filled('password')) {
                $dataToUpdate['password'] = bcrypt($request->password);
            }

            User::where('id', $profile->id)->update($dataToUpdate);

            return redirect('/profile')->with('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            return redirect('/profile')->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function cucianSelesai($id)
    {
        try {
            StatusCucian::create([
                'orderan_id' => $id,
                'status' => 'Cucian Selesai',
                'tgl' => now(),
            ]);

            return redirect('/transaksiSaya')->with('success', 'Status cucian berhasil diperbarui menjadi selesai.');
        } catch (\Exception $e) {
            return redirect('/transaksiSaya')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function laundry()
    {
        $laundry = JenisLaundry::all();
        $jumlahKeranjang = Keranjang::where('user_id', Auth::user()->id)
            ->where('status', '0')
            ->count();
        return view('customers.laundry', compact('laundry', 'jumlahKeranjang'));
    }

    public function paket()
    {
        $paket = PaketLaundry::all();
        $jumlahKeranjang = Keranjang::where('user_id', Auth::user()->id)
            ->where('status', '0')
            ->count();
        return view('customers.paket', compact('paket', 'jumlahKeranjang'));
    }

    public function transaksi()
    {
        $orderan = OrderanOnline::where('user_id', Auth::user()->id)
            ->with('orderan.jenisLaundry')
            ->get();
        $jumlahKeranjang = Keranjang::where('user_id', Auth::user()->id)
            ->where('status', '0')
            ->count();
        $paketSaya = PaketMember::with('paketLaundry.jenisLaundry')->where('user_id', auth()->user()->id)->get();
        // dd($paketSaya[0]->paketLaundry->jenisLaundry->nama);
        return view('customers.transaksi', compact('orderan', 'jumlahKeranjang', 'paketSaya'));
    }

    public function setLocation($id)
    {
        return view('customers.setLocation', compact('id'));
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

            self::updateData(
                $request->id,
                'Lokasi Jemput Diperbarui',
                null
            );

            return redirect('/transaksiSaya')->with('success', 'Lokasi berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect('/transaksiSaya')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function batalkanOrder($id)
    {
        try {
            $orderan = Orderan::where('id', $id)->first();
            if ($orderan) {
                $orderan->delete();
                return redirect('/transaksiSaya')->with('success', 'Order berhasil dibatalkan.');
            } else {
                return redirect('/transaksiSaya')->with('error', 'Order tidak ditemukan.');
            }
        } catch (\Exception $e) {
            return redirect('/transaksiSaya')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function setMetodePembayaran($id, Request $request)
    {
        try {
            $orderan = Orderan::findOrFail($id);
            $orderan->update(['metode_pembayaran' => $request->metode_pembayaran]);
            return response()->json(['success' => true, 'message' => 'Metode pembayaran berhasil diubah.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function paymentPaket($orderanId, Request $request)
    {
        try {
            $orderan = Orderan::findOrFail($orderanId);
            $paketMember = PaketMember::findOrFail($request->paket);

            if($orderan->jenisLaundry->nama !== $paketMember->paketLaundry->jenisLaundry->nama) {
                return response()->json(['error' => false, 'message' => 'Jenis laundry tidak sesuai dengan paket yang dipilih.'], 400);
            }

            if($orderan->berat > $paketMember->kg_sisa) {
                return response()->json(['error' => false, 'message' => 'Berat cucian tidak memenuhi syarat paket.'], 400);
            }

            $orderan->update([
                'is_paket' => 1,
            ]);

            self::updateData(
                $orderanId,
                'Cucian Diproses',
                'Pembayaran Berhasil',
            );

            $paketMember->update([
                'kg_sisa' => $paketMember->kg_sisa - $orderan->berat,
                'kg_terpakai' => $paketMember->kg_terpakai + $orderan->berat,
            ]);

            return response()->json(['success' => true, 'message' => 'Metode pembayaran berhasil diubah.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $orderan = Orderan::findOrFail($request->input('id'));

            Config::$serverKey = config('midtrans.serverKey');
            Config::$isProduction = false;
            Config::$isSanitized = true;
            Config::$is3ds = true;

            $params = array(
                'transaction_details' => array(
                    'order_id' => rand(),
                    'gross_amount' => $orderan->harga + $orderan->orderanOnline->ongkir,
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

    public function paymentSuccess($snap)
    {
        try {
            $orderan = Orderan::where('snap_token', $snap)->firstOrFail();
            self::updateData(
                $orderan->id,
                'Cucian Diproses',
                'Pembayaran Berhasil',
            );
            return redirect()->back()->with('success', 'Pembayaran berhasil');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Pembayaran gagal: ' . $e->getMessage());
        }
    }

    public function cekStatusCucian(Request $request)
    {
        $orderCode = $request->query('order_code');

        $order = Orderan::where('kode_order', $orderCode)->with(['jenisLaundry', 'statusCucian'])->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Kode order tidak ditemukan.'
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'kode_order' => $order->kode_order,
                'jenis_laundry' => $order->jenisLaundry->nama,
                'status' => $order->statusCucian->last()->status ?? 'Belum Ada Status',
            ]
        ]);
    }
}
