<?php

use App\Models\Survey;
use App\Models\SurveyResult;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Orion\Facades\Orion;

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
        if (Auth::user()->role == "student"){
            abort(401);
        }
        return Inertia::render('Surveys/Surveys');
    })->name('surveys');

    /*** Create Survey #1 form ***/
    Route::get('/create-survey', function () {
        if (Auth::user()->role == "student"){
            abort(401);
        }
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
        if (Auth::user()->role == "student"){
            abort(401);
        }
        return Inertia::render('Results/Results', [
            'survey' => Survey::findOrFail($survey_id),
        ]);
    })->name('results');

    /*** Get specific result for specific survey ***/
    Route::get('/{survey_id}/results/{result_id}', function ($survey_id, $result_id) {
        $survey = Survey::findOrFail($survey_id);
        if (Auth::user()->id != $survey->user_id){
            abort(401);
        }
        return Inertia::render('Results/SurveyResult', [
            'survey' => $survey,
            'result' => SurveyResult::findOrFail($result_id)
        ]);
    })->name('show-survey-result');

    /*** Survey Builder ***/
    Route::get('/{survey_id}', function ($survey_id) {
        $survey = Survey::findOrFail($survey_id);

        if (Auth::user()->role != "admin" && $survey->user_id != Auth::user()->id){
            abort(401);
        }

        return Inertia::render('Surveys/BuildSurvey', [
            'survey' => $survey,
            'is_creator' => true,
        ]);
    })->name('survey');

});

Route::prefix('users')->middleware(['auth', 'verified'])->group(function () {
    /*** Users List ***/
    Route::get('/', function () {
        return Inertia::render('Users/Users');
    })->name('users');

    /*** Create user form ***/
    Route::get('/create', function () {
        return Inertia::render('Users/CreateUser');
    })->name('create-user');

    /*** Edit user form ***/
    Route::get('/edit/{user_id}', function ($user_id) {
        return Inertia::render('Users/EditUser', [
            "user" => User::findOrFail($user_id)
        ]);
    })->name('edit-user');
});

Route::prefix('api')->middleware(['auth', 'verified'])->group(function () {

});

Route::group(
    [
        'prefix'     =>  'api',
        'namespace'     =>  'App\Http\Controllers\API',
        'middleware'    =>  ['auth', 'verified'],
    ],
    function () {

        Route::resource('/survey', 'SurveyAPIController', ['only' => [
            'index', 'store', 'update', 'destroy', 'show',
        ]]);

        Route::resource('/survey/{survey}/result', 'SurveyResultAPIController');

        Orion::resource('users', UsersController::class);
    }
);

require __DIR__ . '/auth.php';
