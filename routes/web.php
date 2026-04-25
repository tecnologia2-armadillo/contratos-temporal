<?php

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

use App\Http\Controllers\PersonalController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\PersonalNoVinculadoController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Public Contract Routes
Route::get('/contract/sign/{token}', [ContractController::class, 'show'])->name('contract.show');
Route::post('/contract/sign/{token}', [ContractController::class, 'sign'])->name('contract.sign.post');

Route::get('/contrato/no-vinculado', [PersonalNoVinculadoController::class, 'showSignForm'])->name('contract.no_vinculado.show');
Route::post('/contrato/no-vinculado', [PersonalNoVinculadoController::class, 'sign'])->name('contract.no_vinculado.post');

Route::middleware(['simple_auth'])->group(function () {

    Route::get('/dashboard', [PersonalController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/no-vinculado', [PersonalNoVinculadoController::class, 'index'])->name('dashboard.no_vinculado');
});


