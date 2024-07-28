<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get("/searchComponent", [SearchController::class, "search"]);
Route::post("/searchComponent", [SearchController::class, "search"]);
