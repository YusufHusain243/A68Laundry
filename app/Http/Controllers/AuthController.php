<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function loginCustomer()
    {
        return view('customers.login');
    }

    public function loginCustomerAuth(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required|string',
                'password' => 'required|string',
            ]);

            // dd(auth()->attempt($request->only('username', 'password')));
            if (auth()->attempt($request->only('username', 'password'))) {
                return redirect('/')->with('success', 'Login successful!');
            }

            return redirect('/loginCustomer')->withErrors(['error' => 'Invalid credentials']);
        } catch (\Exception $e) {
            return redirect('/loginCustomer')->withErrors(['error' => $e->getMessage()]);
        }
    }
    
    public function loginStaff()
    {
        return view('staffs.login');
    }

    public function loginStaffAuth(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required|string',
                'password' => 'required|string',
            ]);

            if (auth()->attempt($request->only('username', 'password'))) {
                return redirect('/orderanOffline')->with('success', 'Login successful!');
            }

            return back()->withInput()->withErrors(['error' => 'Invalid credentials']);
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }
   
    public function loginOwner()
    {
        return view('owners.login');
    }

    public function loginOwnerAuth(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required|string',
                'password' => 'required|string',
            ]);

            if (auth()->attempt($request->only('username', 'password'))) {
                return redirect('/dashboardOwner')->with('success', 'Login successful!');
            }

            return back()->withInput()->withErrors(['error' => 'Invalid credentials']);
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function registerCustomer()
    {
        return view('customers.register');
    }

    public function registerCustomerStore(Request $request)
    {
        // dd($request->all());
        try {
            $request->validate([
                'nama' => 'required|string|max:255',
                'no_hp' => 'required|string|max:15',
                'email' => 'required|string|email|max:255',
                'alamat' => 'required|string|max:255',
                'username' => 'required|string|max:255',
                'password' => 'required|string',
            ]);

            User::create([
                'nama' => $request->nama,
                'no_hp' => $request->no_hp,
                'email' => $request->email,
                'alamat' => $request->alamat,
                'username' => $request->username,
                'password' => bcrypt($request->password),
                'role' => 'Member',
            ]);

            // Redirect to the login page with a success message
            return redirect('/loginCustomer')->with('success', 'Registration successful! Please log in.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function logout(){
        auth()->logout();
        return redirect('/')->with('success', 'Logout successful!');
    }
}
