<?php

use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataController;
use App\Http\Controllers\DynamicFieldController;
use App\Http\Controllers\DynamicFormController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\StudentController;
use App\Models\DynamicField;
use App\Models\DynamicForm;
use App\Models\Student;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

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

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/', [PostController::class, 'index'])->name('post');
// Route::resource('/', PostController::class);
Route::resource('students', StudentController::class);

// Route::resource('dynamic-form', DynamicFormController::class); 
Route::get('/dynamic-form', [DynamicFormController::class, 'index'])->name('dynamic-form.index');
Route::get('/dynamic-form/create', [DynamicFormController::class, 'create'])->name('dynamic-form.create');
Route::post('/dynamic-form', [DynamicFormController::class, 'store'])->name('dynamic-form.store');
Route::get('/dynamic-form/{id}/edit', [DynamicFormController::class, 'edit'])->name('dynamic-form.edit');
Route::post('/dynamic-formm', [DynamicFormController::class, 'update'])->name('dynamic-form.update');
Route::get('/dynamic-form/delete/{id}', [DynamicFormController::class, 'destroy']);
