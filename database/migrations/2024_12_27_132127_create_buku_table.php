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
        Schema::create('buku', function (Blueprint $table) {
            $table->id();
            $table->string('judulBuku');
            $table->string('isbn')->nullable();
            $table->string('kategori');
            $table->string('penulis');
            $table->string('penerbit');
            $table->string('tahunTerbit');
            $table->text('halaman');
            $table->text('sinopsis');   
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bukus');
    }
};
