<?php

namespace App\Models;

use App\Models\Movie;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Genre extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'tmdb_id',
    ];

    /**
     * Bootstrap the model and its traits.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        // static::creating(function ($entity) {
        //     if(!static::where('tmdb_id', $entity->tmdb_id)->first()){
        //         logger($entity);
        //     }
        // });
    }

    /**
     * movies relation.
     *
     * @return BelongsToMany
     */
    public function movies() : BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'movie_genre', 'genre_id', 'movie_id')
                    ->withTimestamps()
                    ;
    }
}
