<?php

use App\Http\Controllers\Api\BookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('buku', [BookController::class, 'index']);
Route::get('buku/{id}', [BookController::class, 'show']);
Route::post('buku', [BookController::class, 'store']);
Route::put('buku/{id}', [BookController::class, 'update']);
Route::delete('buku/{id}', [BookController::class, 'destroy']);
