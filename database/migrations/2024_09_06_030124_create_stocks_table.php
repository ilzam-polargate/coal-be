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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->string('foto_item', 250)->nullable();
            $table->string('jenis_batubara', 250);
            $table->string('grade', 50);
            $table->string('size', 50);
            $table->string('kalori', 50);
            $table->integer('jumlah_stok')->unsigned(); // Pastikan ini integer positif
            $table->string('lokasi_simpan', 250);
            $table->string('harga_per_ton', 250);
            $table->text('catatan')->nullable();
            $table->string('nama_alias', 50)->nullable();
            $table->text('detail_stock')->nullable();
            $table->integer('stock_before_update')->nullable('jumlah_stok'); 
            $table->timestamps();
            $table->softDeletes();  // Menambahkan kolom deleted_at
        });        
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
