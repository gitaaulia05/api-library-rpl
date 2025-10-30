<?php

use App\Http\Controllers\AnggotaTest;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//Route::get('/anggota' , [AnggotaTest::class , 'index']);
