<?php

use App\Http\Controllers\JobController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home');
Route::view('/contact', 'contact');

// Jobs resource
Route::resource('jobs', JobController::class);
