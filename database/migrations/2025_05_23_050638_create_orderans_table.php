<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orderans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_laundry_id')->constrained('jenis_laundries')->onDelete('cascade');
            $table->string('kode_order');
            $table->string('berat')->nullable();
            $table->string('harga')->nullable();
            $table->string('metode_pembayaran');
            $table->string('is_offline');
            $table->string('is_paket');
            $table->string('snap_token')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orderans');
    }
};
