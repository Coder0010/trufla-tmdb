<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Database\Eloquent\Collection;

class MovieUnitTest extends TestCase
{
    /** @test */
    public function a_movie_can_has_genres()
    {
        $movie = Movie::factory()->create();
        $genre = Genre::factory()->create();

        $movie->genres()->sync($genre);

        $this->assertDatabaseHas('movie_genre', [
            'genre_id' => $genre->id,
            'movie_id' => $movie->id
        ]);
    }
}
