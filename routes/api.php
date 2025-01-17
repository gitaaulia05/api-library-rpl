<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuPerpustakaan;
use App\Http\Middleware\ApiPerpusMiddleware;
use App\Http\Controllers\AnggotaPerpustakaan;
use App\Http\Controllers\PetugasPerpustakaan;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get("/faker" , [BukuPerpustakaan::class , 'data']);

Route::post("/petugas/login" , [PetugasPerpustakaan::class, 'login']);

    Route::middleware(ApiPerpusMiddleware::class)->group(function(){
        
        Route::get("/petugas/saatIni" , [PetugasPerpustakaan::class, 'getCurrentPetugas']);
        Route::delete("/petugas/logout", [PetugasPerpustakaan::class, 'logout']);
        Route::post("/buku" , [BukuPerpustakaan::class, 'create']);
        Route::post("/buku/{slugNamaBuku}" , [BukuPerpustakaan::class, 'updateBuku']);
        Route::get("/buku" , [BukuPerpustakaan::class, 'search_data']);
        Route::get("/buku/{slugNamaBuku}" , [BukuPerpustakaan::class, 'detail_data']);
        Route::get("/stok-buku-habis" , [BukuPerpustakaan::class, 'bukuHabis']);
        Route::delete("/buku/{slugNamaBuku}" , [BukuPerpustakaan::class, 'deleteBuku']);

        Route::post("/pinjam-buku" , [BukuPerpustakaan::class, 'pinjamBuku']);


        //tambah anggota
        Route::post("/anggota" , [AnggotaPerpustakaan::class, 'tambah_anggota']);
        //search Anggota
        Route::get("/anggota" , [AnggotaPerpustakaan::class, 'search_anggota']);

        //Detail-anggota-peminjaman - tabel list peminjaman buku
        Route::get('/detail-anggota/{slugAnggota}' , [AnggotaPerpustakaan::class , 'detail_anggota']);

        //Search peminjaman buku Anggota - tabel list peminjaman buku
        Route::get("/peminjaman-anggota/{slugAnggota}" , [AnggotaPerpustakaan::class , 'peminjamanAnggota']);

        // detail pengembalian buku
        Route::get('/pengembalian-buku/{idOrder}' , [AnggotaPerpustakaan::class , 'detailOrder']);

        // Post Pengembalian Buku
        Route::post('/pengembalian-simpan/{idOrder}' , [AnggotaPerpustakaan::class , 'simpanPengembalian']);


    });
