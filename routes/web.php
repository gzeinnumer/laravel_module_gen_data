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
    Route::get('/', [ModuleGenDataController::class, 'index'])->name('modulegendata.index');
    Route::get('/regeneratequery/{id}', [ModuleGenDataController::class, 'regenerateqQuery'])->name('modulegendata.regeneratequery');

    Route::get('/updatetable', [ModuleGenDataController::class, 'updateTable'])->name('modulegendata.updatetable');
    Route::get('/regenerateall', [ModuleGenDataController::class, 'regenerateAll'])->name('modulegendata.regenerateall');
    Route::get('/generatefile', [ModuleGenDataController::class, 'generateFile'])->name('modulegendata.generatefile');
    Route::get('/update/{id}', [ModuleGenDataController::class, 'updateShow'])->name('modulegendata.editShow');
    Route::post('/update', [ModuleGenDataController::class, 'updatePerform'])->name('modulegendata.updatePerform');
    Route::get('/json/{id}', [ModuleGenDataController::class, 'getDataById']);
});
