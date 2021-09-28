<?php

namespace App\Servies;

use DB;
use Arr;
use Str;
use Http;
use Storage;
use App\Models\Genre;
use App\Contracts\iPush;
use App\Contracts\iFetch;
use App\Servies\MainServie;

class GenreServie implements iFetch, iPush
{
    // storing all genres fetched from [ movies/list ] api
    public $genres_response = [];

    // storing all genres fetched from [ movies/populer ] or [ movies/top_rated ] api
    public $movies_genres_ids = [];

    // storing all unique genres from both of previous properties
    public $genres = [];

    //***  start setters && getters ***\\
    /**
     * setter for genres response
     */
    public function setGenresResponse($val)
    {
        $this->genres_response = $val;

        // set uniques genres only
        foreach ($this->getMoviesGenresIDs() as $row) {
            $this->setGenres(
                $this->getGenresResponse()->where("tmdb_id", $row)->first()
            );
        }
    }

    /**
     * getter for genres response
     */
    public function getGenresResponse() : object
    {
        return $this->genres_response;
    }

    /**
     * getter for movies genre IDS from stored data in
     */
    public function getMoviesGenresIDs()
    {
        // grep all json files per page
        foreach (Storage::files("json/genres") as $file) {
            $this->movies_genres_ids[] = collect(Storage::get($file));
        }
        // collapse all arrays of json files & remove dublicates &
        $this->movies_genres_ids = collect(
            Arr::collapse(
                collect(Arr::collapse($this->movies_genres_ids))->map(function ($row) {
                    return json_decode($row);
                })
            )
        )->unique()->sort()->values();

        return $this->movies_genres_ids;
    }

    /**
     * setter for movies genre IDS
     */
    public function setGenres($val)
    {
        $this->genres[] = $val;
    }

    /**
     * getter movies genre IDS
     */
    public function getGenres() : array
    {
        return $this->genres;
    }
    //***  end setters && getters ***\\

    /**
     * fetch data from tmdb api
     */
    public function fetch() : void
    {
        $res = Http::get(config("system.base_url")."genre/movie/list", [
            "api_key"  => config("system.api_key"),
            "language" => config("system.language"),
        ]);
        if ($res->successful()) {
            $tmdb_genres = collect($res["genres"])
                ->map(function ($row) {
                    return [
                        "name"    => $row["name"],
                        "tmdb_id" => $row["id"],
                    ];
                })->unique()->sortBy("tmdb_id")->values();

            $this->setGenresResponse($tmdb_genres);

            // add data to local db
            $this->push();
        }
    }

    /**
     * add genres from tmdb to local db
     */
    public function push() : void
    {
        if (!$this->getGenres()) {
            logger('no genres added to local db');
            return;
        }

        logger('push from GENRE service');
        logger(collect($this->getGenres())->toJson());

        foreach ($this->getGenres() as $row) {
            DB::beginTransaction();
            try {
                Genre::updateOrCreate([
                    "tmdb_id" => $row["tmdb_id"],
                ], $row);

                DB::commit();
            } catch (Exception $e) {
                DB::rollback();
                $this->info($e->getMessage());
            }
        }
    }
}
