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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('body');  // Informasi lengkap dari notifikasi
            $table->string('user'); // Nama user yang melakukan request delete
            $table->string('position'); // Posisi user
            $table->timestamp('read_at')->nullable(); // Untuk menandai jika sudah dibaca
            $table->unsignedBigInteger('client_id')->nullable(); // Menambahkan kolom client_id
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade'); // Menambahkan foreign key constraint
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['client_id']); // Hapus foreign key jika rollback
            $table->dropColumn('client_id'); // Hapus kolom client_id
        });

        Schema::dropIfExists('notifications');
    }
};
