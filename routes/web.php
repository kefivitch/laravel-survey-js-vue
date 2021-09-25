<?php

use App\Models\Survey;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/surveys', function () {
    return Inertia::render('Surveys');
})->middleware(['auth', 'verified'])->name('surveys');
Route::get('/results/{survey_id}', function ($survey_id) {

    return Inertia::render('Results', [
        'survey_id' => $survey_id,
        'survey_name' => Survey::find($survey_id)->name,
    ]);
})->middleware(['auth', 'verified'])->name('results');

Route::get('/create-survey', function () {
    return Inertia::render('CreateSurvey');
})->middleware(['auth', 'verified'])->name('create-survey');

Route::get('/build-survey', function () {
    return Inertia::render('BuildSurvey');
})->middleware(['auth', 'verified'])->name('build-survey');

require __DIR__.'/auth.php';
