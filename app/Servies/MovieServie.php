<?php

namespace App\Servies;

use DB;
use Arr;
use Http;
use Storage;
use App\Models\Genre;
use App\Models\Movie;
use App\Contracts\iPush;
use App\Contracts\iFetch;
use App\Servies\GenreServie;
use App\Traits\PaginationTrait;

class MovieServie implements iPush
{
    use PaginationTrait;

    // this property for storing all movies fetched from movies/ api
    public $movies_response;

    //***  start setters && getters ***\\
    /**
     * setter for movies response
     */
    public function setMoviesResponse(object $val)
    {
        $this->movies_response = $val;
    }

    /**
     * getter for movies response
     */
    public function getMoviesResponse() : object
    {
        return $this->movies_response;
    }
    //***  end setters && getters ***\\

    /**
     * fetch data from tmdb api
     */
    public function fetch(int $page, string $type) : void
    {
        $this->setCurrentPage($page);
        $this->setRecordsType($type);

        $res = Http::get(config("system.base_url")."movie/{$this->getRecordsType()}", [
            "api_key"  => config("system.api_key"),
            "language" => config("system.language"),
            "page"     => $this->getCurrentPage()
        ]);
        if ($res->successful()) {
            $tmdb_movies = collect($res["results"])
                ->map(function ($row) {
                    return [
                        "name"           => $row["original_title"],
                        "overview"       => $row["overview"],
                        "tmdb_id"        => $row["id"],
                        "tmdb_genre_ids" => $row["genre_ids"],
                        "tmdb_type"      => $this->getRecordsType(),
                    ];
                });

            $this->setMoviesResponse($tmdb_movies);

            $this->tempDataToStorage();

            // add data to local db
            $this->push();
        }
    }

    /**
     * add movies from tmdb to local db
     */
    public function push() : void
    {
        if (!$this->getMoviesResponse()) {
            logger('no movies added to local db');
            return;
        }

        logger('push from MOVIE service');
        logger($this->getMoviesResponse());

        foreach ($this->getMoviesResponse() as $row) {
            DB::beginTransaction();
            try {
                $entity = Movie::updateOrCreate([
                    "tmdb_id" => $row["tmdb_id"],
                ], $row);
                DB::commit();
            } catch (Exception $e) {
                DB::rollback();
                $this->info($e->getMessage());
            }
        }
    }

    /**
     * save movie genres to local storage disk
     */
    public function tempDataToStorage() : void
    {
        $movie_genres_id_per_page = collect(
            Arr::collapse(collect($this->getMoviesResponse())->pluck("tmdb_genre_ids"))
        )->unique()->sort()->values();
        Storage::append("json/genres/page-".$this->getCurrentPage()."-genre.json", $movie_genres_id_per_page);
    }
}
