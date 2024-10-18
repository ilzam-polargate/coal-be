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
        Schema::create('client_specs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_address_id')->constrained('client_addresses');
            $table->string('jenis_batubara', 50);
            $table->string('grade', 50);
            $table->string('size', 50);
            $table->string('kalori', 50);
            $table->char('status', 50);
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_specs');
    }
};
