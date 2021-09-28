<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait SearchTrait
{
    /**
    * This Scope for returning all data with specific genre id
    */
    public function scopeSearchByGenreID(Builder $builder)
    {
        $genre_id = request('genre_id');
        if (isset($genre_id)) {
            $builder = $builder->whereHas('genres', function ($q) {
                $q->where('id', request('genre_id'));
            });
        }
        return $builder;
    }

    /**
    * This Scope for returning all data with specific movie type
    */
    public function scopeSearchByTmdbType(Builder $builder)
    {
        $tmdb_type = request('tmdb_type');
        if (isset($tmdb_type)) {
            $builder = $builder->where('tmdb_type', $tmdb_type);
        }
        return $builder;
    }
}
