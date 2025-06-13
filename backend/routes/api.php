<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FornecedorController;
use App\Http\Controllers\MaterialController;

Route::apiResource('fornecedores', FornecedorController::class);
Route::apiResource('materiais', MaterialController::class);
