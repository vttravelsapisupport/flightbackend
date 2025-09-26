<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Registry\DestinationRegistry;

class RefreshDestinations extends Command
{
    protected $signature = 'destinations:refresh';
    protected $description = 'Refresh destinations cache from DB';

    public function handle()
    {
        DestinationRegistry::refresh();
        $this->info('Destinations cache refreshed!');
    }
}
