<?php

use App\Http\Controllers\AcademicController;
use App\Http\Controllers\AdmissionController;
use App\Http\Controllers\AttendanceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\DemoController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FaculitiesController;
use App\Http\Controllers\PaperController;
use App\Http\Controllers\PaperFacultyController;
use App\Http\Controllers\PaperStudentController;
use App\Http\Controllers\ProgrammeController;
use App\Http\Controllers\StudentRegisterController;
use App\Http\Controllers\UserCotroller;

Route::post('/student/register', [StudentRegisterController::class, 'register']);
Route::post('/faculity/login', [AuthController::class, 'facultyLogin']);
 Route::post('/faculty/register', [FaculitiesController::class, 'store']);

Route::controller(EventController::class)->group(function () {
    Route::get('/events', 'index');               
    Route::post('/events', 'store');           
    Route::put('/events/{id}', 'update');         
    Route::delete('/events/{id}', 'destroy');     
});
Route::middleware('auth:api')->group(function () {
    Route::post('/faculty/approve/{id}', [FaculitiesController::class, 'approve']);
});

Route::controller(DepartmentController::class)->group(function(){
        Route::get('/departments', 'index');               
        Route::post('/departments', 'store');               

});
Route::controller(ProgrammeController::class)->group(function(){
        Route::get('/programmes', 'index');               
        Route::post('/programmes', 'store');               

});

Route::controller(AcademicController::class)->group(function(){
        Route::get('/academic-years', 'index');               
        Route::post('/academic-years', 'store');               

});

Route::controller(BatchController::class)->group(function(){
        Route::get('/batches', 'index');               
        Route::post('/batches', 'store');               

});
Route::controller(PaperController::class)->group(function () {
    Route::get('/papers', 'index');
    Route::post('/papers', 'store');
    Route::put('/papers/{id}', 'update');
    Route::delete('/papers/{id}', 'destroy');
});

Route::controller(PaperStudentController::class)->group(function () {
    Route::get('/student-papers', 'index');
    Route::post('/student-papers', 'store');
    Route::put('/student-papers/{id}', 'update');
});
Route::controller(PaperFacultyController::class)->group(function () {
    Route::get('/paper-faculities', 'index');
    Route::post('/paper-faculities', 'store');
    Route::delete('/paper-faculities/{id}', 'destroy');
});
Route::controller(AdmissionController::class)->prefix('admissions')->group(function () {
    Route::get('/', 'index');
    Route::get('/{id}', 'show');
    Route::post('/store', 'store');
    Route::put('/update/{id}', 'update');
    Route::delete('/delete/{id}', 'destroy');
});

Route::controller(AttendanceController::class)->group(function () {
    Route::get('/attendances', 'index');
    Route::post('/attendances/bulk-mark', 'markAttendance');
});

Route::controller(DemoController::class)->group(function(){
        Route::get('/collection','collectionExample');
        Route::get('/store','testStorage');
        Route::get('/get-content','getContent');
});
