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
        Schema::create('orders', function (Blueprint $table) {
            $table->string('id_order')->primary();
            $table->string('id_petugas');
            $table->foreign('id_petugas')->references('id_petugas')->on('petugas');
            // $table->string('id_buku');
            // $table->foreign('id_buku')->references('id_buku')->on('bukus');
            $table->string('id_anggota');
            $table->foreign('id_anggota')->references('id_anggota')->on('anggotas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
