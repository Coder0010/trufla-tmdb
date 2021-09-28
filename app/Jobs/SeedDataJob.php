<?php

namespace App\Jobs;

use File;
use Storage;
use App\Models\Movie;
use App\Servies\GenreServie;
use App\Servies\MovieServie;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SeedDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 0;

    public $tries   = 1;

    public $movies = MovieServie::class;

    public $page;

    public $type;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($page, $type)
    {
        $this->page = $page;
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->runMovieServie();
        $this->runGenreServie();
        $this->clearInternalStorage();
    }

    public function runMovieServie()
    {
        $movieService = new MovieServie();
        $movieService->fetch($this->page, $this->type);
    }

    public function runGenreServie()
    {
        $genre = new GenreServie();
        $genre->fetch();
    }

    // remove all files in storage disk
    public function clearInternalStorage()
    {
        File::cleanDirectory("storage/app/public");
        Storage::put(".gitignore", "*\r\n!.gitignore\n");
    }
}
