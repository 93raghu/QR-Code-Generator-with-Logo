<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QRCodeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});



Route::get('/qr-code-generator', [QRCodeController::class, 'index'])->name('qr.code.generator');
Route::post('/generate-qr-code', [QRCodeController::class, 'generate'])->name('generate.qr.code');
