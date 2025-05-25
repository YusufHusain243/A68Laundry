<?php

namespace App\Http\Controllers;

use App\Models\PaketLaundry;
use App\Models\JenisLaundry;
use Illuminate\Http\Request;

class PaketLaundryController extends Controller
{
    public function index()
    {
        $jenisLaundry = JenisLaundry::all();
        $paketLaundry = PaketLaundry::with('jenisLaundry')->get();
        return view('owners.paket_laundry.paket_laundry', compact('paketLaundry', 'jenisLaundry'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_laundry_id' => 'required',
            'harga' => 'required',
            'berat' => 'required'
        ]);

        try {
            PaketLaundry::create([
                'jenis_laundry_id' => $validated['jenis_laundry_id'],
                'harga' => $validated['harga'],
                'berat' => $validated['berat']
            ]);
            return redirect('/paketLaundryOwner')->with('success', 'Data berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect('/paketLaundryOwner')->with('error', 'Data gagal ditambahkan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $jenisLaundry = PaketLaundry::findOrFail($id);
            $jenisLaundry->delete();
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
        $validated = $request->validate([
            'jenis_laundry_id' => 'required',
            'harga' => 'required',
            'berat' => 'required'
        ]);

        try {
            PaketLaundry::where('id', $request->id)->update($validated);
            return redirect('/paketLaundryOwner')->with('success', 'Data berhasil diubah');
        } catch (\Exception $e) {
            return redirect('/paketLaundryOwner')->with('error', 'Data gagal diubah: ' . $e->getMessage());
        }
    }
}
