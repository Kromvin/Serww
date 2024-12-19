<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InfoController;

Route::get('/info/server', [InfoController::class, 'getServerInfo']);
Route::get('/info/client', [InfoController::class, 'getClientInfo']);
Route::get('/info/database', [InfoController::class, 'getDatabaseInfo']);

