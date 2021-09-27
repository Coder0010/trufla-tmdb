rm -rfv vendor/ public/storage bootstrap/cache/*.tmp bootstrap/cache/*.php &&
composer install &&
php artisan storage:link
