<?php

use App\Http\Controllers\ModuleGenDataController;
use Illuminate\Support\Facades\App;
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


Route::get('/', [ModuleGenDataController::class, 'index'])->name('modulegendata.index');

Route::prefix('modulegendata')->group(function () {
    //list
    Route::get('/', [ModuleGenDataController::class, 'index'])->name('modulegendata.index');
    Route::get('/updatetable', [ModuleGenDataController::class, 'updateTable'])->name('modulegendata.updatetable');
    Route::get('/generatefile', [ModuleGenDataController::class, 'generateFile'])->name('modulegendata.generatefile');
    //info
    // Route::get('/find/{id}', [ModuleGenDataController::class, 'findShow'])->name('modulegendata.findShow');
    //update
    Route::get('/update/{id}', [ModuleGenDataController::class, 'updateShow'])->name('modulegendata.editShow');
    Route::post('/update', [ModuleGenDataController::class, 'updatePerform'])->name('modulegendata.updatePerform');
    //delete
    // Route::get('/delete/{id}', [ModuleGenDataController::class, 'deleteShow'])->name('modulegendata.deleteShow');
    // Route::post('/delete', [ModuleGenDataController::class, 'deletePerform'])->name('modulegendata.deletePerform');
    //data by id
    Route::get('/json/{id}', [ModuleGenDataController::class, 'getDataById']);
    //dataTables
    // Route::get('/data', [ModuleGenDataController::class, 'dataTables'])->name('modulegendata.data');
    //chart
    // Route::get('/chart/{date}', [ModuleGenDataController::class, 'getChart']);

    if (App::hasDebugModeEnabled()) {
        //dataTables json
        // Route::get('/json', [ModuleGenDataController::class, 'getData']);
    }
});
