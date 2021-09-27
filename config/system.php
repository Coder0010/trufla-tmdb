<?php

return [
    'base_url'     => env('TMDB_URL', 'https://api.themoviedb.org/3/'),
    'api_key'      => env('TMDB_API_KEY', '157ab6300f5b6acaac0a551c79113dea'),
    'language'     => env('TMDB_API_LANGUAGE', 'en-US'),
    'records_type' => ['top_rated', 'popular'],
];
