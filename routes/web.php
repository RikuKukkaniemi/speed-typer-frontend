<?php

use App\Http\Controllers\HighscoreController;
use App\Http\Controllers\WordsController;
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
    return view('app');
});

Route::get('/words/{language}', [WordsController::class, 'getWords']);
Route::get('/highscores', [HighscoreController::class, 'getHighscores']);

Route::post('/highscores', [HighscoreController::class, 'postHighscore']);
