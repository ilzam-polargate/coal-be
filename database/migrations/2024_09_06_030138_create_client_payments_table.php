<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_order_id')->constrained('client_orders')->onDelete('cascade');
            $table->unsignedBigInteger('client_order_detail_id')->nullable(); // Pastikan ini unsignedBigInteger
            $table->integer('termin_ke');
            $table->decimal('jumlah_bayar', 15, 2);
            $table->date('tgl_jatuh_tempo');
            $table->date('tgl_bayar')->nullable();
            $table->enum('payment_status', ['unpaid', 'paid']);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Foreign key ke tabel client_order_details
            $table->foreign('client_order_detail_id')
                  ->references('id')->on('client_order_details')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_payments');
    }
};
