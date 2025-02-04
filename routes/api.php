<?php

use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');*/

Route::prefix('v1')->group(function () {
    Route::apiResource('users', UserController::class);
    Route::apiResource('companies', CompanyController::class);
    Route::get('companies/{company}/comments', [CompanyController::class, 'getComments']);
    Route::get('companies/{company}/rating', [CompanyController::class, 'getAverageRating']);
    Route::get('companies/top/rated', [CompanyController::class, 'getTopCompanies']);
    Route::apiResource('comments', CommentController::class);
    Route::post('files/upload', [FileController::class, 'store']);
});
