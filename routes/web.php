<?php

use App\Http\Controllers\FinalizeController;
use App\Http\Controllers\ToExcelController;
use App\Http\Controllers\ToPdfController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
// Route::controller(FinalizeController::class)->group(function () {
//     Route::get('/export-excel/{id}', 'exportAssessmentReport');

// });
Route::controller(ToExcelController::class)->group(function () {
    Route::get('/export-excel/{id}', 'exportAssessmentReport');

});
Route::get('/assessment/{id}/pdf', [ToPdfController::class, 'exportPdf']);
