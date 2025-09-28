<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.home');
});

Route::get('/detailfilm', function () {
    return view('pages.detailfilm');
});
