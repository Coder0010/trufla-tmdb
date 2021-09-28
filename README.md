# trugla-tmdb system

This system made by laravel framework as back.
## Step One Clone Repo

    git clone git@github.com:coder0010/trugla-tmdb

or you can download it by the desktop application of github

    https://github.com/coder0010/trugla-tmdb

Switch to the repo folder

    cd trugla-tmdb

---
## Step Two Prepare Project

1 Prepare the project

    bashes/composer.sh

2 Copy .env.example file and make the required configuration changes in the .env file

    cp .env.example ./.env

3 Run this command

    php artisan server:setup
        * choose Create_Database
        * Then choose Migrate_and_Seed

---
## Step Three Testing

    php artisan test
---
## Step Four Seeding

** note please open laravel.log file **

    * php artisan queue:work
    * php artisan fetch:data

---
# Task Core Files

* Commands
  * FetchDataCommand.php

* Jobs
  * SeedDataJob.php  => Seeding greped data from command 
  * UpdateMovieGenreJob.php  => Update movie genres [ pivot ] table

* Servies 
  * MovieServie.php  => Greping data per page
  * GenreServie.php  => Greping data per page from storage [ .json ] files

* Traits
    * SearchTrait
    * PaginationTrait
---

<!-- # Task Requirements Analysis

* Point One 

    * create command to fetch data by [ type & number of records ]

    * seeding the output data to local db.

* Point Two

    * create erd for movies and genres [ categories ] with pivot table 

* Point Three, Four, Five

    * create endpoint the list movies.

    * search by genre_id and sort data. -->
