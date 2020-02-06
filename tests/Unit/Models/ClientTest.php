<?php

namespace Tests\Unit\Models;

use App\Enums\ClientStatus;
use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientTest extends TestCase
{
    use RefreshDatabase;
    use CreatesClients;

    public function testClientWithFrozenSubscriptionsStatus()
    {
        /** @var Client $client */
        $client = $this->createFrozenClients()->first();

        self::assertEquals(
            ClientStatus::FROZEN,
            $client->status,
            'Client with frozen Subscription should have ' . ClientStatus::FROZEN . ' status'
        );
    }

    public function testClientWithNotActivatedSubscriptionStatus()
    {
        /** @var Client $client */
        $client = $this->createNotActivatedClients()->first();

        self::assertEquals(
            ClientStatus::NOT_ACTIVATED,
            $client->status,
            'Client with frozen Subscription should have ' . ClientStatus::NOT_ACTIVATED . ' status'
        );
    }
}
