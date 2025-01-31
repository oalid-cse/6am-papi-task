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

Route::get('job', function () {
    dispatch(new App\Jobs\LoginLogJob(\App\Models\User::find(1)));
    return "Job dispatched";
});
