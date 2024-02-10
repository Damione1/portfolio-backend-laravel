<?php

use App\Http\Controllers\ImageController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\PostController;
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
    Route::apiResource('posts', PostController::class);
    Route::apiResource('images', ImageController::class);
    Route::apiResource('skills', SkillController::class);
    Route::apiResource('experiences', ExperienceController::class);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

Route::prefix('public')->group(function () {
    Route::prefix('{user_id}')->group(function () {
        Route::get('posts', [PostController::class, 'publicIndex']);
        Route::get('posts/{id}', [PostController::class, 'publicShow']);

        Route::get('projects', [ProjectController::class, 'publicIndex']);
        Route::get('projects/{id}', [ProjectController::class, 'publicShow']);

        Route::get('skills', [SkillController::class, 'publicIndex']);
        Route::get('skills/{id}', [SkillController::class, 'publicShow']);

        Route::get('experiences', [ExperienceController::class, 'publicIndex']);
        Route::get('experiences/{id}', [ExperienceController::class, 'publicShow']);
    });
});


// Public routes
Route::group([], function () {
    Route::post('login', [AuthController::class, 'login']);
});
