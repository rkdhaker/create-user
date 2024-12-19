<?php

use Illuminate\Support\Facades\Route;
Use App\Http\Controllers\{UserController};


//create page
Route::get('/', [UserController::class, 'create']);
