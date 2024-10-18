<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('client_orders', function (Blueprint $table) {
            $table->id();
            $table->string('no_po', 50)->unique();
            $table->foreignId('stock_id')->constrained('stocks')->onDelete('cascade');
            $table->foreignId('client_address_id')->constrained('client_addresses');
            $table->foreignId('client_spec_id')->constrained('client_specs');
            $table->char('status_order', 50);
            $table->date('order_date');
            $table->integer('total_order');
            $table->decimal('total_tagihan', 15, 2)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });        
    }

    public function down(): void
    {
        Schema::dropIfExists('client_orders');
    }
};
