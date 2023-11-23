<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ToolController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('auth/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['apiJwt']], function () {
    Route::get('users', [UserController::class, 'index']);

    Route::get('tools', [ToolController::class, 'getTools']);

    Route::post('tools', [ToolController::class, 'createTool']);

    Route::delete('tools/{id}', [ToolController::class, 'deleteTool']);
});
