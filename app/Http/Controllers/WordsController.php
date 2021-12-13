<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class WordsController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param string $language
     * @return Response
     */
    public function getWords(string $language): Response
    {
        $words = Http::get(config('app.backend_url') . "/wordLists/$language")->collect()->toArray()[0]['words'];
        shuffle($words);

        return response(['words' => $words]);
    }
}
