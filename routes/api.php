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
| Here is where you can register API routes for your application.
| These routes are loaded by the RouteServiceProvider and all of them
| will be assigned to the "api" middleware group.
|
*/

// Authentication
Route::post('/register', [AuthController::class, 'register']); // Controller now calls AuthService
Route::post('/login', [AuthController::class, 'login']);

// Authenticated routes
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']); // Controller uses AuthService
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Admin routes
    Route::middleware('role:admin')->group(function () {
        Route::apiResource('users', UserController::class);        // CRUD via UserService
        Route::apiResource('categories', CategoryController::class); // CRUD via CategoryService
        Route::apiResource('trainings', TrainingController::class);  // CRUD via TrainingService
        Route::get('trainings/statistics', [TrainingController::class, 'trainingStatistics']); // via TrainingService
    });

    // Formateur routes
    Route::middleware('role:formateur')->group(function () {
        Route::get('my-trainings', [TrainingController::class, 'myTrainings']); // Service fetches trainings
        Route::post('trainings/{training}/grade', [EnrollmentController::class, 'grade']); // Service handles grading
    });

    // Apprenant routes
    Route::middleware('role:apprenant')->group(function () {
        Route::post('trainings/{training}/enroll', [EnrollmentController::class, 'enroll']); // Service handles enrollment
        Route::get('my-enrollments', [EnrollmentController::class, 'myEnrollments']);       // Service fetches enrollments
    });

    // Entreprise routes
    Route::prefix('entreprise')->group(function () {
        Route::post('/employees', [EntrepriseController::class, 'createEmployee']);       // Service creates employee
        Route::post('/purchase-seats/{training}', [EntrepriseController::class, 'purchaseSeats']); // Service handles seats
        Route::post('/enroll-employee/{training}', [EntrepriseController::class, 'enrollEmployee']); // Service enrolls employee
        Route::get('/employees/progress', [EntrepriseController::class, 'employeesProgress']); // Service fetches progress
    });
});

// Public route for listing users (optional, kept as is)
Route::get('/users', [UserController::class, 'index']); // Could internally call UserService
