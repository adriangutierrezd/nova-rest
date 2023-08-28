<?php

use libs\Route;
use App\Controllers\BaseController;
use App\Controllers\TokenController;



Route::post('/api/get-token', [TokenController::class, 'generateToken']);


Route::dispatch(str_replace('public/', '', $_SERVER['REQUEST_URI']), $_SERVER['REQUEST_METHOD']);



