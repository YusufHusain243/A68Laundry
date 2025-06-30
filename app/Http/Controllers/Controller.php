<?php

namespace App\Http\Controllers;

use App\Models\StatusCucian;
use App\Models\StatusPembayaran;
use Illuminate\Support\Facades\Http;

abstract class Controller
{
    protected function updateData($id, $statusCucian, $statusPembayaran = null)
    {
        try {
            if ($statusPembayaran !== null) {
                StatusPembayaran::create([
                    'orderan_id' => $id,
                    'status'     => $statusPembayaran,
                    'tgl'        => now(),
                ]);
            }

            StatusCucian::create([
                'orderan_id' => $id,
                'status'     => $statusCucian,
                'tgl'        => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil update'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data gagal update: ' . $e->getMessage()
            ], 500);
        }
    }

    protected function sendWa($target, $message)
    {
        $response = Http::withHeaders([
            'Authorization' => 'WpXrvWiw7xZM2bHJwcuy',
        ])->post('https://api.fonnte.com/send', [
            'target' => $target,
            'message' => $message,
        ]);

        return $response->body();
    }
}
