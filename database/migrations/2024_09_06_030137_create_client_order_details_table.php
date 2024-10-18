<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientOrderDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('client_order_details', function (Blueprint $table) {
            $table->id(); // Ini akan secara otomatis menjadi unsignedBigInteger
            $table->unsignedBigInteger('client_order_id');
            $table->string('no_po', 50);
            $table->integer('jumlah_order');
            $table->string('image')->nullable();
            $table->char('status');
            $table->text('reason')->nullable();
            $table->timestamps();

            // Foreign key ke tabel client_orders
            $table->foreign('client_order_id')->references('id')->on('client_orders')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('client_order_details');
    }
}


