<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Movie;

class MovieFeatureTest extends TestCase
{
    /** @test */
    public function get_all_movies()
    {
        $movie = Movie::factory()->create();

        $response = $this->get('/api/movies')
            ->assertSee($movie->name)
            ->assertSee($movie->overview);

        $response->assertStatus(200);
    }
}
