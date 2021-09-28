<?php

namespace App\Models;

use Storage;
use App\Models\Genre;
use App\Traits\SearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Movie extends Model
{
    use HasFactory, SearchTrait;

    protected $fillable = [
        'name',
        'overview',
        'tmdb_id',
        'tmdb_genre_ids',
        'tmdb_type',
    ];

    protected $casts = [
        'id'             => 'int',
        'tmdb_genre_ids' => 'array',
    ];

    /**
     * update movies genres method
     */
    public static function updateMovieGenres()
    {
        // here i need to pass only the movies i seed it at current page
        foreach (self::all() as $entity) {
            if ($entity['tmdb_genre_ids'] && is_array($entity['tmdb_genre_ids'])) {
                foreach ($entity['tmdb_genre_ids'] as $genre_id) {
                    //add genres to povit table and prevent attach duplicates
                    $entity->genres()->syncWithoutDetaching(Genre::where('tmdb_id', $genre_id)->first());
                }
            }
        }
    }

    /**
     * genres relation.
     *
     * @return BelongsToMany
     */
    public function genres() : BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'movie_genre', 'movie_id', 'genre_id')
                    ->withTimestamps()
                    ;
    }
}
