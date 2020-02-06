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

    // <editor-fold desc="Status">
    // <editor-fold desc="Attribute">
    public function testClientWithoutSubscriptionsStatusAttribute()
    {
        /** @var Client $client */
        $client = $this->createClients()->first();

        self::assertEquals(
            ClientStatus::NO_SUBSCRIPTION,
            $client->status,
            'Client without Subscriptions should have ' . ClientStatus::NO_SUBSCRIPTION . ' status'
        );
    }

    public function testClientWithSingleActiveSubscriptionStatusAttribute()
    {
        /** @var Client $client */
        $client = $this->createActiveClients()->first();

        self::assertEquals(
            ClientStatus::ACTIVE,
            $client->status,
            'Client without Subscriptions should have ' . ClientStatus::ACTIVE . ' status'
        );
    }

    public function testClientWithSingleFrozenSubscriptionStatusAttribute()
    {
        /** @var Client $client */
        $client = $this->createFrozenClients()->first();

        self::assertEquals(
            ClientStatus::FROZEN,
            $client->status,
            'Client with single frozen Subscription should have ' . ClientStatus::FROZEN . ' status'
        );
    }

    public function testClientWithSingleNotActivatedSubscriptionStatusAttribute()
    {
        /** @var Client $client */
        $client = $this->createNotActivatedClients()->first();

        self::assertEquals(
            ClientStatus::NOT_ACTIVATED,
            $client->status,
            'Client with single frozen Subscription should have ' . ClientStatus::NOT_ACTIVATED . ' status'
        );
    }

    public function testClientWithSingleExpiredSubscriptionStatusAttribute()
    {
        /** @var Client $client */
        $client = $this->createExpiredClients()->first();

        self::assertEquals(
            ClientStatus::EXPIRED,
            $client->status,
            'Client with single expired Subscription should have ' . ClientStatus::EXPIRED . ' status'
        );
    }

    public function testClientWithActiveAndExpiredSubscriptionsStatusAttribute()
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

    public function testClientWithActiveAndNotActiveSubscriptionsStatusAttribute()
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

    public function testClientWithActiveAndFrozenSubscriptionsStatusAttribute()
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
    // </editor-fold>

    // <editor-fold desc="Scope">

    private function statusScopeTester($client, $status)
    {
        $queryResult = Client::status($status)->get();

        $this->assertEquals(1, $queryResult->count());

        /** @var Client $firstClient */
        $firstClient = $queryResult->first();

        $this->assertEquals($client->client_id, $firstClient->client_id);

        $this->assertEquals(
            $status,
            $firstClient->status,
            'Status scope doesn\'t match attribute'
        );

        foreach (ClientStatus::getValues() as $_status) {
            if ($_status === $status)
                continue;

            $queryResult = Client::status($_status)->get();

            self::assertEmpty(
                $queryResult,
                'Query result for ' . $_status . ' have to be empty'
            );
        }
    }

    public function testClientWithoutSubscriptionsStatusScope()
    {
        /** @var Client $client */
        $client = $this->createClients()->first();

        $this->statusScopeTester($client, ClientStatus::NO_SUBSCRIPTION);
    }

    public function testClientWithSingleActiveSubscriptionStatusScope()
    {
        /** @var Client $client */
        $client = $this->createActiveClients()->first();

        $this->statusScopeTester($client, ClientStatus::ACTIVE);
    }

    public function testClientWithSingleFrozenSubscriptionStatusScope()
    {
        /** @var Client $client */
        $client = $this->createFrozenClients()->first();

        $this->statusScopeTester($client, ClientStatus::FROZEN);
    }

    public function testClientWithSingleNotActivatedSubscriptionStatusScope()
    {
        /** @var Client $client */
        $client = $this->createNotActivatedClients()->first();

        $this->statusScopeTester($client, ClientStatus::NOT_ACTIVATED);
    }

    public function testClientWithSingleExpiredSubscriptionStatusScope()
    {
        /** @var Client $client */
        $client = $this->createExpiredClients()->first();

        $this->statusScopeTester($client, ClientStatus::EXPIRED);
    }

    public function testClientWithActiveAndExpiredSubscriptionsStatusScope()
    {
        /** @var Client $client */
        $client = $this->createClients()->first();

        $client->subscriptions()->save(factory(Subscription::class)->make());
        $client->subscriptions()->save(factory(Subscription::class)->states('expired')->make());

        $this->statusScopeTester($client, ClientStatus::ACTIVE);
    }

    public function testClientWithActiveAndNotActiveSubscriptionsStatusScope()
    {
        /** @var Client $client */
        $client = $this->createExpiredClients()->first();

        $client->subscriptions()->save(factory(Subscription::class)->make());
        $client->subscriptions()->save(factory(Subscription::class)->states('not_activated')->make());

        $this->statusScopeTester($client, ClientStatus::ACTIVE);
    }

    public function testClientWithActiveAndFrozenSubscriptionsStatusScope()
    {
        /** @var Client $client */
        $client = $this->createExpiredClients()->first();

        $client->subscriptions()->save(factory(Subscription::class)->states('frozen')->make());
        $client->subscriptions()->save(factory(Subscription::class)->make());

        $this->statusScopeTester($client, ClientStatus::FROZEN);
    }
    // </editor-fold>
    // </editor-fold>
}
