<?php

namespace App\Http\Controllers;

use App\Models\Orderan;
use App\Models\PaketMember;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPendapatanPaket = PaketMember::join('paket_laundries', 'paket_members.paket_laundry_id', '=', 'paket_laundries.id')
            ->sum('paket_laundries.harga');
        $totalPendapatan = Orderan::where('is_paket', '0')->sum('harga') + $totalPendapatanPaket;
        $totalOrderan = Orderan::count();
        $totalMember = User::count();

        return view('owners.dashboard.dashboard', compact('totalPendapatan', 'totalOrderan', 'totalMember'));
    }
}
