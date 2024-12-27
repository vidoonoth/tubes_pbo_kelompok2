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
        Schema::create('usulan', function (Blueprint $table) {
            $table->id();
            $table->string('judulBuku', 255);
            $table->string('isbn', 255)->nullable();
            $table->string('kategori', 255);
            $table->string('penulis', 255);
            $table->string('penerbit', 255);
            $table->string('tahunTerbit', 4);
            $table->date('tanggalPengusulan');    
            // $table->unsignedBigInteger('user_id');
            
            // $table->enum('status', ['diproses', 'diterima', 'tersedia', 'ditolak' ])->default('diproses');

            // // Menambahkan kolom user_id untuk relasi dengan tabel users
            $table->foreignId('id_pengusul')->constrained('pengusul')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usulans');
    }
};
