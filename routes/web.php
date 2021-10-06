<?php

use App\Models\Survey;
use App\Models\SurveyResult;
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
})->name('welcome');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::prefix('surveys')->middleware(['auth', 'verified'])->group(function () {
    /*** Surveys List ***/
    Route::get('/', function () {
        return Inertia::render('Surveys/Surveys');
    })->name('surveys');

    /*** Create Survey #1 form ***/
    Route::get('/create-survey', function () {
        return Inertia::render('Surveys/CreateSurvey');
    })->name('create-survey');

    /*** Run a survey ***/
    Route::get('/{survey_slug}/show', function ($survey_slug) {

        return Inertia::render('Surveys/SurveyShow', [
            'survey' => Survey::where('slug', $survey_slug)->firstOrFail(),
        ]);
    })->name('survey-show');

    /*** Get all results for specific survey ***/
    Route::get('/{survey_id}/results', function ($survey_id) {

        return Inertia::render('Results/Results', [
            'survey' => Survey::findOrFail($survey_id),
        ]);
    })->name('results');

    /*** Get specific result for specific survey ***/
    Route::get('/{survey_id}/results/{result_id}', function ($survey_id, $result_id) {
        return Inertia::render('Results/SurveyResult', [
            'survey' => Survey::findOrFail($survey_id),
            'result' => SurveyResult::findOrFail($result_id)
        ]);
    })->name('show-survey-result');

    /*** Survey Builder ***/
    Route::get('/{survey_id}', function ($survey_id) {
        return Inertia::render('Surveys/BuildSurvey', [
            'survey' => Survey::findOrFail($survey_id),
            'is_creator' => true,
        ]);
    })->name('survey');

});

Route::prefix('users')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/{survey_id}', function ($survey_id) {
        return Inertia::render('Surveys/BuildSurvey', [
            'survey' => Survey::findOrFail($survey_id),
            'is_creator' => true,
        ]);
    })->name('survey');
});


require __DIR__ . '/auth.php';
