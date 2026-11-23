<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route menu API dipindahkan ke routes/web.php.
// Route di file ini (routes/api.php) berjalan lewat middleware group "api"
// yang tidak memuat sesi login, jadi middleware 'auth' di sini tidak pernah
// mengenali admin yang sudah login lewat sesi web biasa.