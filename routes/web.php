<?php

use GetCandy\Hub\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;
use Servnx\GetCandyFavorite\Http\Livewire\Pages\FavoritesIndex;

Route::group([
    'prefix'     => config('getcandy-hub.system.path', 'hub'),
    'middleware' => ['web'],
], function () {
    Route::group([
        'middleware' => [
            Authenticate::class,
        ],
    ], function () {
        Route::group([
            'prefix' => 'favorites'
        ], function () {
            Route::get('/', FavoritesIndex::class)->name('hub.favorites.index');
        });
    });
});
