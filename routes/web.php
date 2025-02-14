<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PayslipController;

Route::get('/', function () {
    return view('welcome');
});
