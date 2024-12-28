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
        Schema::create('bukus', function (Blueprint $table) {
            $table->string("id_buku")->primary();
            $table->string("slug" , 100);
            $table->string("nama_buku" , 100);
            $table->string("gambar_buku" , 100);
            $table->string("gambar_qr" , 100);
            $table->string("nama_penulis" , 100);
            $table->string("nama_penerbit" , 100);   
            $table->year("tahun_terbit"); 
            $table->integer("jumlah_buku");   
            $table->boolean("buku_tersedia" );  
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
