<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\TimeSeriesController;

Route::get('/upload', [TimeSeriesController::class, 'showUploadForm'])->name('upload.form');
Route::post('/upload', [TimeSeriesController::class, 'uploadCSV'])->name('upload.csv');
Route::post('/upload-processed-csv', [TimeSeriesController::class, 'uploadProcessedCSV'])->name('upload.processed.csv');

