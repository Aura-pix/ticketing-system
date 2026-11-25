<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class TicketApiTest extends TestCase
{
    use RefreshDatabase;

    private const API_KEY = 'test-ticket-api-key';

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('services.ticketing.api_key', self::API_KEY);
    }

    public function test_it_creates_and_lists_tickets(): void
    {
        $ticket = [
            'title' => 'Printer is offline',
            'description' => 'The second-floor printer cannot connect to the network.',
            'priority' => 'high',
        ];
        $headers = ['X-API-Key' => self::API_KEY];

        $this->postJson('/api/tickets', $ticket, $headers)
            ->assertCreated()
            ->assertJsonStructure([
                'data' => ['id', 'title', 'description', 'priority'],
            ])
            ->assertJsonPath('data.title', $ticket['title'])
            ->assertJsonPath('data.description', $ticket['description'])
            ->assertJsonPath('data.priority', $ticket['priority']);

        $this->assertDatabaseHas('tickets', $ticket);

        $this->getJson('/api/tickets', $headers)
            ->assertOk()
            ->assertJsonPath('data.0.title', $ticket['title']);
    }

    public function test_ticket_fields_are_required(): void
    {
        $this->postJson('/api/tickets', [], ['X-API-Key' => self::API_KEY])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['title', 'description', 'priority']);
    }

    public function test_ticket_endpoints_return_forbidden_without_a_valid_api_key(): void
    {
        $this->getJson('/api/tickets')
            ->assertForbidden()
            ->assertExactJson(['message' => 'Forbidden.']);

        $this->postJson('/api/tickets', [], ['X-API-Key' => 'wrong-key'])
            ->assertForbidden()
            ->assertExactJson(['message' => 'Forbidden.']);
    }

    public function test_ticket_endpoints_fail_closed_when_no_api_key_is_configured(): void
    {
        Config::set('services.ticketing.api_key', null);

        $this->getJson('/api/tickets', ['X-API-Key' => self::API_KEY])
            ->assertForbidden();
    }
}
