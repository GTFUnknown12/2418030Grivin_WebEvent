<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembelis', function (Blueprint $table) {
            $table->id('id_pembeli'); // Primary key dengan nama custom
            $table->string('nama_pembeli');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('alamat');
            $table->string('email')->unique();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->date('tanggal_lahir');
            $table->timestamp('create_at')->useCurrent();
            $table->boolean('is_admin')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembelis');
    }
};