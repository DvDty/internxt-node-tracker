<?php

namespace App\Console\Commands;

use App\Models\Address;
use Illuminate\Console\Command;

class AddressClear extends Command
{
    protected $signature = 'address:cleanup';

    public function handle(): int
    {
        $addresses = Address::all();

        $addresses->each(function ($address) {
            if (!$address->numberOfNodes) {
                $address->delete();

                $this->info('Deleting ' . $address->ip);
            }
        });

        $this->info('Addresses cleared up successfully.');

        return 0;
    }
}
