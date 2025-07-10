<?php

namespace App\Http\Controllers;

use App\Models\JenisLaundry;
use App\Models\Keranjang;
use App\Models\Orderan;
use App\Models\OrderanOnline;
use App\Models\PaketLaundry;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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

    public function laundry(){
        $laundry = JenisLaundry::all();
        $jumlahKeranjang = Keranjang::where('user_id', Auth::user()->id)
            ->where('status', '0')
            ->count();
        return view('customers.laundry', compact('laundry','jumlahKeranjang'));
    }
    
    public function paket(){
        $paket = PaketLaundry::all();
        $jumlahKeranjang = Keranjang::where('user_id', Auth::user()->id)
            ->where('status', '0')
            ->count();
        return view('customers.paket', compact('paket','jumlahKeranjang'));
    }
    
    public function transaksi(){
        $orderan = OrderanOnline::where('user_id', Auth::user()->id)
            ->with('orderan.jenisLaundry')
            ->get();
        $jumlahKeranjang = Keranjang::where('user_id', Auth::user()->id)
            ->where('status', '0')
            ->count();
        return view('customers.transaksi', compact('orderan','jumlahKeranjang'));
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
}
