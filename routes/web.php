<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.home');
});

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/registrasi', function () {
    return view('auth.registrasi');
});

Route::get('/film', function () {
    return view('pages.film');
});

Route::get('/detailfilm', function () {
    return view('pages.detailfilm');
});

Route::get('/kursi', function () {
    return view('pages.kursi');
});