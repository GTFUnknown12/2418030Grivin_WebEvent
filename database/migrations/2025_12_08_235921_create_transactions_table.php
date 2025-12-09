<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique();
            
            // Foreign keys
            $table->unsignedBigInteger('pembeli_id');
            $table->unsignedBigInteger('ticket_id');
            
            $table->decimal('amount', 10, 2);
            $table->enum('type', ['payment', 'refund', 'withdrawal']);
            $table->enum('status', ['pending', 'completed', 'failed']);
            $table->text('description')->nullable();
            $table->string('payment_method');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('pembeli_id')->references('id_pembeli')->on('pembelis')->onDelete('cascade');
            $table->foreign('ticket_id')->references('id_tiket')->on('tickets')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};