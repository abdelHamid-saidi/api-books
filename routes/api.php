<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/ping', function () {
    return response()->json([
        "message" => "pong"
    ]);
});

Route::post('/register', [UserController::class, "register"]);
Route::post('/login', [UserController::class, "login"])->middleware("throttle:10,1");

Route::apiResource("books", BookController::class)->only("index", "show");

Route::middleware("auth:sanctum")->group(function () {
    Route::post('/logout', [UserController::class, "logout"]);
    Route::apiResource("books", BookController::class)->only(["store", "update", "destroy"]);
});
