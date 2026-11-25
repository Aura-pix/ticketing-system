# Ticketing System API

A Laravel JSON API for creating and listing support tickets. Ticket data is stored in SQLite.

## Setup

Requirements: PHP 8.3+ and Composer.

```sh
composer install
php -r "file_exists('.env') || copy('.env.example', '.env');"
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
php artisan key:generate
php artisan migrate
```

Set `TICKETING_API_KEY` in `.env` to a long, unique secret. Both endpoints require this value in the `X-API-Key` header. Requests are forbidden with HTTP 403 if the key is missing, incorrect, or not configured. Do not commit the real key.

Start the local server with `php artisan serve`. The examples below assume the server is running at `http://localhost:8000` and `API_KEY` is set in your shell to the same value as `TICKETING_API_KEY`.

## Endpoints

### `POST /api/tickets`

All three fields are required and must be strings: `title`, `description`, and `priority`.

Happy path:

```sh
curl -i -X POST http://localhost:8000/api/tickets \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -H "X-API-Key: $API_KEY" \
  -d '{
    "title": "Printer is offline",
    "description": "The second-floor printer cannot connect to the network.",
    "priority": "high"
  }'
```

The API responds with HTTP 201:

```json
{
  "data": {
    "id": 1,
    "title": "Printer is offline",
    "description": "The second-floor printer cannot connect to the network.",
    "priority": "high"
  }
}
```

Sad path (omitting the required `priority` field):

```sh
curl -i -X POST http://localhost:8000/api/tickets \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -H "X-API-Key: $API_KEY" \
  -d '{
    "title": "Printer is offline",
    "description": "The second-floor printer cannot connect to the network."
  }'
```

Laravel responds with HTTP 422 and identifies the missing field:

```json
{
  "message": "The priority field is required.",
  "errors": {
    "priority": [
      "The priority field is required."
    ]
  }
}
```

### `GET /api/tickets`

Returns all tickets as a JSON `data` array. Include the same `X-API-Key` header:

```sh
curl -i http://localhost:8000/api/tickets \
  -H "Accept: application/json" \
  -H "X-API-Key: $API_KEY"
```
