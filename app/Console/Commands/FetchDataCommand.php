<?php

namespace App\Console\Commands;

use File;
use Storage;
use App\Models\Genre;
use App\Models\Movie;
use App\Jobs\SeedDataJob;
use App\Jobs\UpdateMovieGenreJob;
use App\Servies\GenreServie;
use App\Servies\MovieServie;
use Illuminate\Console\Command;

class FetchDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "fetch:data";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "fetching data from tmdb with using some options like type of data & number of records";

    /**
     * number of records asked by user
     */
    public $requested_records;

    /**
     * default count records from api
     */
    public $default_records = 20;

    /**
     * count of pages depend on requested records
     */
    public $pages_count;

    /**
     * setter for requested records
     */
    public function setRequestedRecords(int $val) : void
    {
        $this->requested_records = $val;
    }

    /**
     * getter for requested records
     */
    public function getRequestedRecords() : int
    {
        return $this->requested_records;
    }

    /**
     * getter for dafault records from api
     */
    public function getDefaultRecords() : int
    {
        return $this->default_records;
    }

    /**
     * setter for pages count
     */
    public function setPagesCount(int $val) : void
    {
        $this->pages_count = $val;
    }

    /**
     * setter for pages count
     */
    public function getPagesCount() : int
    {
        $number = $this->getRequestedRecords() / $this->getDefaultRecords();
        return floor($number);
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->call("log:clear");

        $type_choice = $this->choice(
            "Movies type of you want?",
            config("system.records_type"),
            "top_rated",
        );

        $records_choice = $this->choice(
            "Which source would you like to use?",
            [20, 100, 200, 500, 1000, 2000, 5000, 10000, ],
            20
        );

        $this->setRequestedRecords($records_choice);

        $this->output->progressStart($this->getPagesCount());
        for ($i = 1; $i <= $this->getPagesCount(); $i++) {
            dispatch(new SeedDataJob($i, $type_choice));
            sleep(1);
            $this->output->progressAdvance();
        }
        $this->output->progressFinish();

        dispatch(new UpdateMovieGenreJob($i, $type_choice));
    }
}
