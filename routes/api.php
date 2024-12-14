<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuPerpustakaan;
use App\Http\Middleware\ApiPerpusMiddleware;
use App\Http\Controllers\PetugasPerpustakaan;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
Route::post("/petugas/login" , [PetugasPerpustakaan::class, 'login']);

    Route::middleware(ApiPerpusMiddleware::class)->group(function(){
        
        Route::get("/petugas/saatIni" , [PetugasController::class, 'getCurrentPetugas']);

        Route::post("/buku" , [BukuPerpustakaan::class, 'create']);
        Route::get("/buku" , [BukuPerpustakaan::class, 'search_data']);
        Route::get("/buku/{slugNamaBuku}" , [BukuPerpustakaan::class, 'detail_data']);
        
        Route::post("/buku/{slugNamaBuku}" , [BukuPerpustakaan::class, 'updateBuku']);
        Route::delete("/buku/{slugNamaBuku}" , [BukuPerpustakaan::class, 'deleteBuku']);
    });