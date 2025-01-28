<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('login', function () {
    return "Login";
})->name('login');

Route::middleware('auth')->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
