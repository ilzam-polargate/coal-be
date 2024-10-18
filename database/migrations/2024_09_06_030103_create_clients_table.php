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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('company', 50);
            $table->string('company_code', 50);
            $table->string('nama_purchasing', 250);
            $table->string('alamat', 250);
            $table->string('email', 250);
            $table->string('nomor_telep', 250);
            $table->text('deletion_reason')->nullable();
            $table->boolean('deletion_requested')->default(false); // Untuk menandai pengajuan penghapusan
            $table->boolean('deletion_approved')->default(false);  // Untuk menandai persetujuan
            $table->timestamps();
            $table->softDeletes();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
