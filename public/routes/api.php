<?php

use libs\Route;
use App\Controllers\BaseController;




Route::dispatch(str_replace('public/', '', $_SERVER['REQUEST_URI']), $_SERVER['REQUEST_METHOD']);



