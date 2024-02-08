<?php

use App\Http\Controllers\ImageController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\SkillController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('projects', ProjectController::class);
    Route::apiResource('images', ImageController::class);
    Route::apiResource('skills', SkillController::class);
    Route::apiResource('experiences', ExperienceController::class);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

// Public routes
Route::group([], function () {
    Route::post('login', [AuthController::class, 'login']);
    //Route::apiResource('projects', ProjectController::class)->only(['index', 'show']);
    //Route::apiResource('images', ImageController::class)->only(['index', 'show']);
});
