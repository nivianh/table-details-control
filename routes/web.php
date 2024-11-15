<?php

use Botble\Base\Facades\AdminHelper;
use Illuminate\Support\Facades\Route;
use Plugin\TableDetailsControl\Http\Controllers\PageController;
use Plugin\TableDetailsControl\Http\Controllers\PostController;

AdminHelper::registerRoutes(function (): void {
    Route::group([
        'prefix' => 'pages',
        'as' => 'pages.',
        'controller' => PageController::class,
        'permission' => 'pages.index',
    ], function (): void {
        Route::get('show/{page}', 'show')
            ->wherePrimaryKey('page')
            ->name('show');
    });

    Route::group([
        'prefix' => 'posts',
        'as' => 'posts.',
        'controller' => PostController::class,
        'permission' => 'posts.index',
    ], function (): void {
        Route::get('show/{post}', 'show')
            ->wherePrimaryKey('post')
            ->name('show');
    });
});
