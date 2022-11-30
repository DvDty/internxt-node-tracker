<?php

namespace App\Console\Commands;

use App\Models\Node;
use App\Services\Internxt;
use Carbon\Carbon;
use Illuminate\Console\Command;

class NodesUpdate extends Command
{
    protected $signature = 'nodes:update';

    public function handle(): int
    {
        $start = Carbon::now();

        resolve(Internxt::class)->updateNodes();

        Node::where('updated_at', '<', $start)->delete();

        $this->info('Nodes updated successfully.');

        return 0;
    }
}
