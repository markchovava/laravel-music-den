<?php

use App\Http\Controllers\AppInfoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CampaignCompanyController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\ClaimedVoucherController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\GeneratedVoucherController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ProgramVoucherController;
use App\Http\Controllers\RedeemVoucherController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserAlbumController;
use App\Http\Controllers\UserArtistController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserTrackController;
use App\Http\Controllers\VoucherPriceController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::group(['middleware' => ['auth:sanctum']], function() {

    Route::get('/logout', [AuthController::class, 'logout']);
    Route::get('/auth-user', [AuthController::class, 'view']);
    Route::post('/auth-user', [AuthController::class, 'update']);
    Route::post('/password', [AuthController::class, 'password']);

    Route::prefix('user-artist')->group(function() {
        Route::get('/', [UserArtistController::class, 'index']);
        Route::post('/', [UserArtistController::class, 'store']);
        Route::get('/by-auth', [UserArtistController::class, 'indexByAuth']);
        Route::delete('/{id}', [UserArtistController::class, 'delete']);
    });

    Route::prefix('user-album')->group(function() {
        Route::get('/', [UserAlbumController::class, 'index']);
        Route::post('/', [UserAlbumController::class, 'store']);
        Route::get('/by-auth', [UserAlbumController::class, 'indexByAuth']);
        Route::delete('/{id}', [UserAlbumController::class, 'delete']);
    });

    Route::prefix('user-track')->group(function() {
        Route::get('/', [UserTrackController::class, 'index']);
        Route::post('/', [UserTrackController::class, 'store']);
        Route::get('/by-auth', [UserTrackController::class, 'indexByAuth']);
        Route::delete('/{id}', [UserTrackController::class, 'delete']);
    });


});
