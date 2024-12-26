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
        Schema::create('pengusul', function (Blueprint $table) {
            $table->id();                    
            $table->string('namaLengkap', 255);
            $table->string('username', 255)->unique();
            $table->string('nik');
            $table->string('nomorTelepon');
            $table->string('jenisKelamin', 255);
            $table->string('email', 255)->unique();
            $table->string('password');            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengusul');
    }
};
