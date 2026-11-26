# Ticketing System

A Laravel JSON API for creating and listing support tickets, backed by SQLite. The repository is a small test project for documentation tooling and automated documentation updates.

## Requirements

- PHP 8.3+
- Composer

## Run locally

```sh
composer install
php -r "file_exists('.env') || copy('.env.example', '.env');"
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
php artisan key:generate
php artisan migrate
php artisan serve
```

Set `TICKETING_API_KEY` in `.env` before making API requests. See the [API documentation](docs/api.md) for the endpoint contract.
