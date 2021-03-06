<?php declare(strict_types = 1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class HighscoreController extends Controller
{
    public const AMOUNT_OF_HIGHSCORES = 10;

    public function getHighscores()
    {
        $response = Http::get(config('app.backend_url') . '/highscores/' . self::AMOUNT_OF_HIGHSCORES);

        return $response->json();
    }

    public function postHighscore(Request $request)
    {
        $response = Http::post(config('app.backend_url') . '/highscores', ['name' => $request->name, 'score' => $request->score]);

        return $response->json();
    }
}
