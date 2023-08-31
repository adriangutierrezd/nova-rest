<?php

require_once __DIR__.'/../../app/config/constants.php';

use libs\Route;
use App\Controllers\BaseController;
use App\Controllers\TokenController;



Route::post('/api/get-token', [TokenController::class, 'generateToken']);
Route::post('/api/validate-token', [TokenController::class, 'validateToken']);


Route::dispatch(str_replace('public/', '', $_SERVER['REQUEST_URI']), $_SERVER['REQUEST_METHOD']);



