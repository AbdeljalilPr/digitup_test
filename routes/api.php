<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\EntrepriseController;
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
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum','role:admin'])->group(function(){
    Route::apiResource('users', UserController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('trainings', TrainingController::class);
});

Route::middleware(['auth:sanctum','role:formateur'])->group(function(){
    Route::get('my-trainings', [TrainingController::class, 'myTrainings']);

    Route::post('trainings/{training}/grade', [EnrollmentController::class, 'grade']);
});

Route::middleware(['auth:sanctum','role:apprenant'])->group(function(){
    Route::post('trainings/{training}/enroll', [EnrollmentController::class, 'enroll']);
    Route::get('my-enrollments', [EnrollmentController::class, 'myEnrollments']);
});

Route::get('/users', [UserController::class, 'index']);

Route::middleware(['auth:sanctum','role:admin'])
    ->get('trainings/statistics', [TrainingController::class, 'trainingStatistics']);

Route::middleware(['auth:sanctum'])->group(function () {

    Route::prefix('entreprise')->group(function () {

        Route::post('/employees', [EntrepriseController::class, 'createEmployee']);
        Route::post('/purchase-seats/{training}', [EntrepriseController::class, 'purchaseSeats']);
        Route::post('/enroll-employee/{training}', [EntrepriseController::class, 'enrollEmployee']);
        Route::get('/employees/progress', [EntrepriseController::class, 'employeesProgress']);

    });

});
