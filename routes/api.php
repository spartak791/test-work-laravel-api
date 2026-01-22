<?php

use App\Http\Controllers\TaskController;
use App\Http\Middleware\EnsureJsonRequest;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

Route::apiResource('tasks', TaskController::class)
    ->middlewareFor(
        ['store', 'update'],
        [EnsureJsonRequest::class]
    )
    ->missing(function () {
        throw new NotFoundHttpException('Resource not found.');
    });
