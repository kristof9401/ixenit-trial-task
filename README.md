# ixenit-trial-task
This is the trial task for job application to Ixenit company.

# Requirements
- PHP v8.2
- npm v9.5.1 or higher
- Composer v2.2.21
- MariaDB

# How to init the application
- Prepare an empty database which is managed in MariaDB RDBMS
- Clone .env.example and rename it to .env
- Set DB_HOST, DB_NAME and DB credentials in .env file
- run `composer install` command
- run `npm install` command
- run `npm run build` command
- run `php artisan migrate` command
- run `php artisan key:generate` command
- run `php artisan serve` to start the application in locale environment
- You can find more info [here](https://laravel.com/docs/10.x/installation)