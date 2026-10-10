<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;

//Route::get(`/user`, function (Request $request) {
//    return $request->user();
//})->middleware(`auth:sactum`);

Route::get("/", function() {
    return response()->json([
        "message" => "Hello Bro"
    ],200);
});

Route::post("/register", [AuthController::class, "register"]);
Route::post("/login", [AuthController::class, "login"]);
Route::post("/logout", [AuthController::class, "logout"])->middleware("auth:sanctum");
Route::get("/user", [AuthController::class, "user"])->middleware("auth:sanctum");
Route::apiResource('categories', CategoryController::class)->middleware("auth:sanctum");
Route::get('/me', [AuthController::class, "me"])->middleware("auth:sanctum");
Route::get("/categories", [CategoryController::class, "index"])->middleware("auth:sanctum");
Route::post("/categories", [CategoryController::class, "store"])->middleware("auth:sanctum");
Route::get("/categories/{id}", [CategoryController::class, "show"])->middleware("auth:sanctum");
Route::patch("/categories/{id}", [CategoryController::class, "update"])->middleware("auth:sanctum");