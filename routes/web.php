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
use App\Http\Controllers\ContratoController;
use App\Http\Controllers\FirmaContratoController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Firma de contrato por ID (público)
Route::get('/firmar/{contrato_id}', [FirmaContratoController::class, 'show'])->name('firmar.show');
Route::post('/firmar/{contrato_id}', [FirmaContratoController::class, 'validar'])->name('firmar.validar');
Route::get('/firmar/{contrato_id}/sign/{token}', [FirmaContratoController::class, 'showSign'])->name('firmar.sign.show');
Route::post('/firmar/{contrato_id}/sign/{token}', [FirmaContratoController::class, 'processSign'])->name('firmar.sign.post');
Route::get('/firmar/{contrato_id}/sign-nv/{nv_id}', [FirmaContratoController::class, 'showSignNV'])->name('firmar.sign_nv.show');
Route::post('/firmar/{contrato_id}/sign-nv/{nv_id}', [FirmaContratoController::class, 'processSignNV'])->name('firmar.sign_nv.post');

// Registro + Firma para nuevos no vinculados
Route::get('/firmar/{contrato_id}/registro-nv', [FirmaContratoController::class, 'showRegistroNV'])->name('firmar.registro_nv.show');
Route::post('/firmar/{contrato_id}/registro-nv', [FirmaContratoController::class, 'processRegistroNV'])->name('firmar.registro_nv.post');

// Public Contract Routes (legacy)
Route::get('/contract/sign/{token}', [ContractController::class, 'show'])->name('contract.show');
Route::post('/contract/sign/{token}', [ContractController::class, 'sign'])->name('contract.sign.post');

Route::get('/contrato/no-vinculado', [PersonalNoVinculadoController::class, 'showSignForm'])->name('contract.no_vinculado.show');
Route::post('/contrato/no-vinculado', [PersonalNoVinculadoController::class, 'sign'])->name('contract.no_vinculado.post');

Route::middleware(['simple_auth'])->group(function () {

    // Dashboard → redirige directamente al módulo de contratos
    Route::get('/dashboard', function () {
        return redirect()->route('contratos.index');
    })->name('dashboard');

    // AJAX: datos de personal (usado en detalle de contrato)
    Route::get('/dashboard/personal-data', [PersonalController::class, 'index'])->name('dashboard.personal_data');
    Route::get('/dashboard/no-vinculado', [PersonalNoVinculadoController::class, 'index'])->name('dashboard.no_vinculado');

    // Módulo de Contratos
    Route::get('/contratos', [ContratoController::class, 'index'])->name('contratos.index');
    Route::post('/contratos', [ContratoController::class, 'store'])->name('contratos.store');
    Route::put('/contratos/{id}', [ContratoController::class, 'update'])->name('contratos.update');
    Route::get('/contratos/{id}/detalle', [ContratoController::class, 'detalle'])->name('contratos.detalle');
    Route::get('/contratos/{id}/personal', [ContratoController::class, 'personalData'])->name('contratos.personal');
    Route::get('/contratos/{id}/personal-no-vinculado', [ContratoController::class, 'personalNVData'])->name('contratos.personal_nv');
});


