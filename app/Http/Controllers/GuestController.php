<?php

namespace App\Http\Controllers;

use App\Models\JenisLaundry;
use App\Models\Orderan;
use App\Models\PaketLaundry;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function index()
    {
        $jenisLaundry = JenisLaundry::all();
        $paketLaundry = PaketLaundry::all();
        return view('guests.main', compact('jenisLaundry', 'paketLaundry'));
    }

    public function cekStatusCucian(Request $request)
    {
        $kode_order = $request->input('nomor_pesanan');
        $data = Orderan::with(['jenisLaundry', 'statusCucian'])
            ->where('kode_order', $kode_order)
            ->get();
        return response()->json(['status' => 'success', 'data' => $data]);
    }
}
