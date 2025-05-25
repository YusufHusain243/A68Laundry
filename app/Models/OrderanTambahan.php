<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderanTambahan extends Model
{
    /** @use HasFactory<\Database\Factories\OrderanTambahanFactory> */
    use HasFactory;
    protected $guarded = ['id'];

    public function orderan(){
        return $this->belongsTo(Orderan::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
