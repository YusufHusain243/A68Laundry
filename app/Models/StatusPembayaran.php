<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusPembayaran extends Model
{
    /** @use HasFactory<\Database\Factories\StatusPembayaranFactory> */
    use HasFactory;
    protected $guarded = ['id'];

    public function orderan(){
        return $this->belongsTo(Orderan::class);
    }
}
