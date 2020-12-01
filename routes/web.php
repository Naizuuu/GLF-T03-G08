<?php

use App\Http\Controllers\InicioController;
use App\Http\Controllers\AutomatasController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', InicioController::class)->name('home');

Route::get('/automatas', [AutomatasController::class, 'index'])->name('automatas');

Route::get('/automatas/afd/', [AutomatasController::class, 'automata_afd'])->name('automata_afd');

Route::get('/automatas/ap/', [AutomatasController::class, 'automata_ap'])->name('automata_ap');