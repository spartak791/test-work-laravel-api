<?php

use App\Http\Controllers\TaskController;
use App\Http\Middleware\EnsureJsonRequest;
use Illuminate\Support\Facades\Route;

Route::apiResource('tasks', TaskController::class)
    ->middlewareFor(
        ['store', 'update'],
        [EnsureJsonRequest::class]
    );
