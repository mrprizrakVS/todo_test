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

Route::get('/tasks', [\App\Http\Controllers\TasksController::class, 'index'])->name('tasks.index');
Route::post('/tasks', [\App\Http\Controllers\TasksController::class, 'store'])->name('tasks.store');
Route::post('/tasks/{task}', [\App\Http\Controllers\TasksController::class, 'completedTask'])->name('tasks.completed');
Route::post('/delete-task/{id}', [\App\Http\Controllers\TasksController::class, 'destroy'])->name('tasks.destroy');
