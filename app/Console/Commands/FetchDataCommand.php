<?php

namespace App\Console\Commands;

use File;
use Storage;
use App\Models\Genre;
use App\Models\Movie;
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

    public function __construct()
    {
        parent::__construct();
        $this->clearInternalStorage();
    }

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
        $this->call('log:clear');

        $type_choice = $this->choice(
            "Movies type of you want?",
            config("system.records_type"),
            "popular"
        );

        $records_choice = $this->choice(
            "Which source would you like to use?",
            [20, 100, 200, 500, 1000, 2000, 5000, 10000, ],
            20
        );

        $this->setRequestedRecords($records_choice);

        $this->output->progressStart($this->getPagesCount());
        for ($i = 1; $i <= $this->getPagesCount(); $i++) {
            $movieService = new MovieServie();
            $movieService->fetch($i, $type_choice);

            sleep(1);
            $this->output->progressAdvance();
        }

        $genre = new GenreServie();
        $genre->fetch();

        $this->output->progressFinish();

        $this->info("added movies");
        $this->table(["name", "created at"], Movie::get(["name", "created_at"]));

        $this->info("added genres");
        $this->table(["name", "created at"], Genre::get(["name", "created_at"]));
    }

    // remove all files in storage disk
    public function clearInternalStorage()
    {
        File::cleanDirectory("storage/app/public");
        Storage::put(".gitignore", "*\r\n!.gitignore\n");
    }

    public function __destruct()
    {
        $this->clearInternalStorage();
        Movie::updateMovieGenres();
    }
}
