<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\MetricsController;
use App\Http\Controllers\NodeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MetricsController::class, 'home'])->name('home');

Route::prefix('/addresses')
    ->controller(AddressController::class)
    ->name('addresses.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{address}', 'show')->name('show');
        Route::post('/change-email', 'changeEmail')->name('change-email');
    });

Route::prefix('/nodes')
    ->controller(NodeController::class)
    ->name('nodes.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{nodeId}', 'show')->name('show');
    });
