# trugla-tmdb system

This system made by laravel framework as back.
## Step One
### Clone the repository

    git clone git@github.com:coder0010/trugla-tmdb

or you can download it by the desktop application of github

    https://github.com/coder0010/trugla-tmdb

Switch to the repo folder

    cd trugla-tmdb

## Step Two

* Run This bash file **bashes/refresh.sh** at your terminal for prepare the project

* Copy .env.local file and make the required configuration changes in the .env file

* run this command **php artisan server:setup**

    1- create **database** and after it finished.

    2- run migrate_and_seed and after it finished.

## Step Three

* Run php artisan fetch:data
## Step Four

    run **php artisan test** to run tesing of project

---

# Task Requirements Analysis

* Point One 

    * create command to fetch data by [ type & number of records ]

    * seeding the output data to local db.

* Point Two

    * create erd for movies and genres [ categories ] with pivot table 

* Point Three, Four, Five

    * create endpoint the list movies.

    * search by genre_id and sort data.
