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
    public function createClient($num = 1) {
        return factory(Client::class, $num)->create();
    }

    /**
     * @param int $num Desired amount of items
     * @return Collection
     */
    public function createFrozenClients($num = 1) {
        return $this->createClient($num)
            ->each(function (Client $client) {
                $client->subscriptions()->save(factory(Subscription::class)->states('frozen')->make());
            });
    }

    /**
     * @param int $num Desired amount of items
     * @return Collection
     */
    public function createNotActivatedClients($num = 1) {
        return $this->createClient($num)
            ->each(function (Client $client) {
                $client->subscriptions()->save(factory(Subscription::class)->states('not_activated')->make());
            });
    }
}
