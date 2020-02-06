<?php


namespace Tests\Unit\Models;


use App\Models\Client;
use App\Models\Subscription;
use Illuminate\Support\Collection;

trait CreatesClients
{
    /**
     * @param int $num Desired amount of items
     * @return Collection
     */
    public function createClients($num = 1) {
        return factory(Client::class, $num)->create();
    }

    /**
     * @param int $num Desired amount of items
     * @return Collection
     */
    public function createActiveClients($num = 1) {
        return $this->createClients($num)
            ->each(function (Client $client) {
                $client->subscriptions()->save(factory(Subscription::class)->make());
            });
    }

    /**
     * @param int $num Desired amount of items
     * @return Collection
     */
    public function createFrozenClients($num = 1) {
        return $this->createClients($num)
            ->each(function (Client $client) {
                $client->subscriptions()->save(factory(Subscription::class)->states('frozen')->make());
            });
    }

    /**
     * @param int $num Desired amount of items
     * @return Collection
     */
    public function createNotActivatedClients($num = 1) {
        return $this->createClients($num)
            ->each(function (Client $client) {
                $client->subscriptions()->save(factory(Subscription::class)->states('not_activated')->make());
            });
    }

    /**
     * @param int $num Desired amount of items
     * @return Collection
     */
    public function createExpiredClients($num = 1) {
        return $this->createClients($num)
            ->each(function (Client $client) {
                $client->subscriptions()->save(factory(Subscription::class)->states('expired')->make());
            });
    }
}
