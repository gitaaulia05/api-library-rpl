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
        Schema::create('detail_order' , function (Blueprint $table) {
            $table->string('id_detail_order')->primary();
            $table->string('id_order' , 100);
            $table->foreign('id_order')->references('id_order')->on('orders');
            $table->string('id_buku');
            $table->foreign('id_buku')->references('id_buku')->on('bukus');
            $table->char('buku_dikembalikan' , 1)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_order');
    }
};
