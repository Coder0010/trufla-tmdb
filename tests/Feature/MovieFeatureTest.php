<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Genre;
use App\Models\Movie;

class FullFeatureTest extends TestCase
{
    /** @test */
    public function a_movie_can_be_added_to_db()
    {
        $movie = Movie::factory()->create();

        $this->assertDatabaseHas('movies', $movie->toArray());
    }

    // /** @test */
    public function a_genre_can_be_added_to_db()
    {
        $genre = Genre::factory()->create();

        $this->assertDatabaseHas('genres', $genre->toArray());
    }

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

    /** @test */
    public function get_all_movies()
    {
        $movie = Movie::factory()->create();

        $response = $this->get('/api/movies')
            ->assertSee($movie->name)
            ->assertSee($movie->overview);

        $response->assertStatus(200);
    }

    /** @test */
    public function get_all_genres()
    {
        $genre = Genre::factory()->create();

        $response = $this->get('/api/genres')
            ->assertSee($genre->name)
            ->assertSee($genre->overview);

        $response->assertStatus(200);
    }
}
