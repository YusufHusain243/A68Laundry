<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisLaundry extends Model
{
    /** @use HasFactory<\Database\Factories\JenisLaundryFactory> */
    use HasFactory;
    protected $guarded = ['id'];

    public function paketLaundry(){
        return $this->hasMany(PaketLaundry::class);
    }

    public function orderan(){
        return $this->hasMany(Orderan::class);
    }
}
