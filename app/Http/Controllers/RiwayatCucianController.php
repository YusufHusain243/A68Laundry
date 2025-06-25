<?php

namespace App\Http\Controllers;

use App\Models\Orderan;
use Illuminate\Http\Request;

class RiwayatCucianController extends Controller
{
    public function index(){
        $data = Orderan::with(['jenisLaundry', 'statusCucian'])
            ->get();
        return view('guests.riwayat_cucian',compact('data'));
    }
}
