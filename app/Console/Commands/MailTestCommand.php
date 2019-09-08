<?php

namespace App\Console\Commands;

use App\Models\Client;
use App\Notifications\SubscriptionGained;
use Illuminate\Console\Command;
use Notification;

class MailTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $clients = Client::query()->where('email', 'LIKE', '%explabs%')->get();

        $this->table(['ID', 'Name', 'Phone number'], $clients->map(function (Client $client) {
            return $client->only(['client_id', 'name', 'phone_number']);
        }));

        Notification::send($clients, new SubscriptionGained());
    }
}
