<?php

use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/Users')->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::post('/Index', 'index')->name('Dashboard.Users.Index');
        Route::post('/Store', 'store')->name('Dashboard.Users.Store');
        Route::post('/Edit/{id}', 'edit')->name('Dashboard.Users.Edit');
        Route::put('/Update/{id}', 'update')->name('Dashboard.Users.Update');
        Route::delete('/Delete', 'delete')->name('Dashboard.Users.Delete');
        Route::put('/Restore', 'restore')->name('Dashboard.Users.Restore');
    });
});
