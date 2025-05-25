<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaketMember extends Model
{
    /** @use HasFactory<\Database\Factories\PaketMemberFactory> */
    use HasFactory;
    protected $guarded = ['id'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function paketLaundry(){
        return $this->belongsTo(PaketLaundry::class);
    }
}
