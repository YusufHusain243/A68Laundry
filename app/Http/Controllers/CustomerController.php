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
            ->get();
        $jumlahKeranjang = Keranjang::where('user_id', Auth::user()->id)
            ->where('status', '0')
            ->count();
        return view('customers.transaksi', compact('orderan','jumlahKeranjang'));
    }
}
