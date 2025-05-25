<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusCucian extends Model
{
    /** @use HasFactory<\Database\Factories\StatusCucianFactory> */
    use HasFactory;
    protected $guarded = ['id'];

    public function orderan(){
        return $this->belongsTo(Orderan::class);
    }
}
