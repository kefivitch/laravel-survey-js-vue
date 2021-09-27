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

Route::get('/surveys/{survey_id}/results', function ($survey_id) {

    return Inertia::render('Results', [
        'survey_id' => $survey_id,
        'survey_name' => Survey::find($survey_id)->name,
    ]);
})->middleware(['auth', 'verified'])->name('results');

Route::get('/surveys/{survey_id}', function ($survey_id) {

    return Inertia::render('BuildSurvey', [
        'survey' => Survey::findOrFail($survey_id),
        'is_creator' => true,
    ]);
})->middleware(['auth', 'verified'])->name('survey');

Route::get('/surveys/{survey_slug}/show', function ($survey_slug) {

    return Inertia::render('SurveyShow', [
        'survey' => Survey::where('slug',$survey_slug)->firstOrFail(),
    ]);
})->middleware(['auth', 'verified'])->name('survey-show');

Route::get('/create-survey', function () {
    return Inertia::render('CreateSurvey');
})->middleware(['auth', 'verified'])->name('create-survey');

Route::get('/build-survey', function () {
    return Inertia::render('BuildSurvey');
})->middleware(['auth', 'verified'])->name('build-survey');

require __DIR__.'/auth.php';
