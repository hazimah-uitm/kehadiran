## About

A basic template which have been installed with:
- User Authentication
- User Role Access Permission (Spatie)
- Activity log (Spatie)

The system is built with:
- Laravel 5.5
- PHP 7.1.1
- Node 12.22.12
- Bootstrap 5.2.0
- Composer 2.2.6

## Using the template

For the owner, please go to your GitHub and navigate to this repository. Then click "Use as Template" and enter the repo name.

For other than owner, please use fork.

## Installation

1. After pull the code from git, open terminal and run "composer install" to install all the dependencies
2. Run "npm install" to install all packages
3. Then run "cp .env.example .env"
4. In .env file, edit DB_DATABASE= to your local database name
5. Run "php artisan key:generate" to generate laravel application key
6. Open mysql phpmyadmin, create database. Make sure the name is same with you have declare in .env.
7. Run "php artisan migrate" and "php artisan db:seed"
8. After done import, run "php artisan serve"
9. You can access the local system by using link http://127.0.0.1:8000

## License

The Laravel framework is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
