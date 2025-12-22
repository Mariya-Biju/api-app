<?php

use App\Http\Controllers\AcademicController;
use App\Http\Controllers\AdmissionController;
use App\Http\Controllers\AttendanceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\BatchProgrammeController;
use App\Http\Controllers\DemoController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FaculitiesController;
use App\Http\Controllers\FinalizeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ListPaymentController;
use App\Http\Controllers\PaperAssessmentController;
use App\Http\Controllers\PaperController;
use App\Http\Controllers\PaperFacultyController;
use App\Http\Controllers\PaperStudentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProgrammeController;
use App\Http\Controllers\StudentMarkController;
use App\Http\Controllers\StudentRegisterController;
use App\Http\Controllers\ToExcelController;
use App\Http\Controllers\UserCotroller;
use App\Http\Controllers\ToPdfController;


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

// Route::controller(DemoController::class)->group(function(){
//     Route::get('/collection','collectionExample');
//     Route::get('/store','testStorage');
//     Route::get('/get-content','getContent');
// });

Route::controller(BatchProgrammeController::class)->group(function () {
    Route::get('/programme-batches', 'index');
    Route::get('/students', 'getStudents');

});
Route::controller(PaperAssessmentController::class)->group(function () {
    Route::post('/paper-assessement', 'store');
    Route::get('/paper-assessement', 'index');
    Route::delete('/paper-assessement/delete/{id}', 'destroy');
    Route::put('/paper-assessement/update/{id}', 'edit');
});

Route::controller(PaperAssessmentController::class)->group(function () {
    Route::post('/paper-assessement', 'store');
    Route::get('/paper-assessement', 'index');
    Route::delete('/paper-assessement/delete/{id}', 'destroy');
    Route::put('/paper-assessement/update/{id}', 'edit');
});

Route::controller(StudentMarkController::class)->group(function () {
    Route::post('/mark-assignment', 'store');
    Route::get('/mark-assignment', 'index');
    Route::delete('/mark-assignment/delete/{id}', 'destroy');
});
Route::controller(FinalizeController::class)->group(function () {
    Route::get('/student-mark', 'index');

});
Route::controller(ToExcelController::class)->group(function () {
    Route::get('/export-excel/{id}', 'exportAssessmentReport');

});
Route::get('/assessment/{id}/pdf', [ToPdfController::class, 'exportPdf']);
Route::post('/create-invoice', [InvoiceController::class, 'store']);
Route::post('/payment', [PaymentController::class, 'manualPayment']);
Route::get('/invoice-list/{id}', [ListPaymentController::class, 'list']);
