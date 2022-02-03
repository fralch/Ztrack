<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Paneles\PanelController;
use App\Http\Controllers\LoginController;
use Inertia\Inertia;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
// ----------- LOGIN  -------------------
Route::get('/', [LoginController::class, 'login' ])->name('/');
Route::post('/validar_usuario', [LoginController::class, 'validarUsuario' ])->name('validar.usuario');
Route::get('/board', [PanelController::class, 'board'])->name('paneles.board');
