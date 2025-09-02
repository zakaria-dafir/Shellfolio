<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TerminalController;

Route::get('/', function () {
    return view('terminal');
});

Route::post('/cmd', [TerminalController::class, 'handle'])->name('cmd');