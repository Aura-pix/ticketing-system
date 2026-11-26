# Ticketing System API

The API is served locally at `http://localhost:8000` when started with `php artisan serve`.

## Authentication

Every endpoint requires the `X-API-Key` request header. Its value must match `TICKETING_API_KEY` in the application environment. A missing or incorrect header, or an unset server key, returns HTTP `403`:

```json
{
  "message": "Forbidden."
}
```

Send `Accept: application/json` to receive JSON responses.

## `GET /api/tickets`

Returns all tickets, ordered by descending ID. The response wraps the list in a `data` array:

```json
{
  "data": [
    {
      "id": 1,
      "title": "Printer is offline",
      "description": "The second-floor printer cannot connect to the network.",
      "priority": "high"
    }
  ]
}
```

Each ticket uses the `TicketResource` shape: `id`, `title`, `description`, and `priority`.

```sh
curl -i http://localhost:8000/api/tickets \
  -H "Accept: application/json" \
  -H "X-API-Key: $API_KEY"
```

## `POST /api/tickets`

Creates a ticket and returns HTTP `201` with the created ticket in a `data` object.

| Field | Type | Required | Constraints |
| --- | --- | --- | --- |
| `title` | string | Yes | Maximum 255 characters |
| `description` | string | Yes | None |
| `priority` | string | Yes | Maximum 32 characters |

Example request:

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

Successful response:

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

If a required field is missing, a field is not a string, or a maximum length is exceeded, the API returns HTTP `422` with validation details. For example, omitting `priority` returns:

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
