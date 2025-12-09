<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id('id_tiket');
            
            // Foreign key harus reference id_pembeli bukan id
            $table->unsignedBigInteger('pembeli_id');
            
            $table->string('judul_tiket');
            $table->integer('jumlah_tiket');
            $table->decimal('harga_satuan', 10, 2);
            $table->decimal('total_harga', 10, 2);
            $table->enum('metode_pembayaran', ['Transfer Bank', 'E-Wallet', 'COD']);
            $table->enum('status_pembayaran', ['pending', 'completed', 'failed'])->default('pending');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('pembeli_id')->references('id_pembeli')->on('pembelis')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};