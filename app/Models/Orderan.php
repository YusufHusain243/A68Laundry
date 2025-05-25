<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orderan extends Model
{
    /** @use HasFactory<\Database\Factories\OrderanFactory> */
    use HasFactory;
    protected $guarded = ['id'];

    public function orderanOffline(){
        return $this->hasOne(OrderanOffline::class);
    }

    public function orderanOnline(){
        return $this->hasOne(OrderanOnline::class);
    }

    public function statusCucian(){
        return $this->hasMany(StatusCucian::class);
    }

    public function statusPembayaran(){
        return $this->hasMany(StatusPembayaran::class);
    }

    public function jenisLaundry(){
        return $this->belongsTo(JenisLaundry::class);
    }

    public function orderanTambahan(){
        return $this->hasMany(OrderanTambahan::class);
    }
}
