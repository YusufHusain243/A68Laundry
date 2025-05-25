<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaketLaundry extends Model
{
    /** @use HasFactory<\Database\Factories\PaketLaundryFactory> */
    use HasFactory;
    protected $guarded = ['id'];

    public function jenisLaundry(){
        return $this->belongsTo(JenisLaundry::class);
    }

    public function paketMember(){
        return $this->hasMany(PaketMember::class);
    }
}
