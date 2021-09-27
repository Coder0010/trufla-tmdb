<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Http\Request;
use App\Http\Requests\SearchRequest;
use App\Http\Resources\GenreResource;
use App\Http\Resources\MovieResource;

class ApiController extends Controller
{
    /**
     * Display a listing of the movies.
     *
     * @return \Illuminate\Http\Response
     */
    public function getMovies(SearchRequest $request)
    {
        $results = Movie::searchByGenreID()->SearchByTmdbType()->get();
        return $this->apiJsonResponse([
            "data" => MovieResource::collection($results),
        ]);
    }

    /**
     * Display a listing of the genres.
     *
     * @return \Illuminate\Http\Response
     */
    public function getGenres()
    {
        $results = Genre::latest()->get();
        return $this->apiJsonResponse([
            "data" => GenreResource::collection($results),
        ]);
    }
}
