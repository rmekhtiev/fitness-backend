<?php

namespace Tests\Unit\Models;

use App\Enums\ClientStatus;
use App\Models\Client;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientTest extends TestCase
{
    use RefreshDatabase;
    use CreatesClients;

    public function testClientWithoutSubscriptionsStatus()
    {
        /** @var Client $client */
        $client = $this->createClient()->first();

        self::assertEquals(
            ClientStatus::NO_SUBSCRIPTION,
            $client->status,
            'Client without Subscriptions should have ' . ClientStatus::NO_SUBSCRIPTION . ' status'
        );
    }

    public function testClientWithSingleActiveSubscriptionStatus()
    {
        /** @var Client $client */
        $client = $this->createActiveClients()->first();

        self::assertEquals(
            ClientStatus::ACTIVE,
            $client->status,
            'Client without Subscriptions should have ' . ClientStatus::ACTIVE . ' status'
        );
    }

    public function testClientWithSingleFrozenSubscriptionStatus()
    {
        /** @var Client $client */
        $client = $this->createFrozenClients()->first();

        self::assertEquals(
            ClientStatus::FROZEN,
            $client->status,
            'Client with single frozen Subscription should have ' . ClientStatus::FROZEN . ' status'
        );
    }

    public function testClientWithSingleNotActivatedSubscriptionStatus()
    {
        /** @var Client $client */
        $client = $this->createNotActivatedClients()->first();

        self::assertEquals(
            ClientStatus::NOT_ACTIVATED,
            $client->status,
            'Client with single frozen Subscription should have ' . ClientStatus::NOT_ACTIVATED . ' status'
        );
    }

    public function testClientWithSingleExpiredSubscriptionStatus()
    {
        /** @var Client $client */
        $client = $this->createExpiredClients()->first();

        self::assertEquals(
            ClientStatus::EXPIRED,
            $client->status,
            'Client with single expired Subscription should have ' . ClientStatus::EXPIRED . ' status'
        );
    }

    public function testClientWithActiveAndExpiredSubscriptionsStatus()
    {
        /** @var Client $client */
        $client = $this->createExpiredClients()->first();

        $client->subscriptions()->save(factory(Subscription::class)->make());
        $client->subscriptions()->save(factory(Subscription::class)->states('expired')->make());

        self::assertEquals(
            ClientStatus::ACTIVE,
            $client->status,
            'Client with both active and expired Subscription should have ' . ClientStatus::ACTIVE . ' status'
        );
    }

    public function testClientWithActiveAndNotActiveSubscriptionsStatus()
    {
        /** @var Client $client */
        $client = $this->createExpiredClients()->first();

        $client->subscriptions()->save(factory(Subscription::class)->make());
        $client->subscriptions()->save(factory(Subscription::class)->states('not_activated')->make());

        self::assertEquals(
            ClientStatus::ACTIVE,
            $client->status,
            'Client with both active and not active Subscription should have ' . ClientStatus::ACTIVE . ' status'
        );
    }

    public function testClientWithActiveAndFrozenSubscriptionsStatus()
    {
        /** @var Client $client */
        $client = $this->createExpiredClients()->first();

        $client->subscriptions()->save(factory(Subscription::class)->states('frozen')->make());
        $client->subscriptions()->save(factory(Subscription::class)->make());

        self::assertEquals(
            ClientStatus::FROZEN,
            $client->status,
            'Client with both active and frozen Subscription should have ' . ClientStatus::FROZEN . ' status'
        );
    }
}
