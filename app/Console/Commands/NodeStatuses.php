<?php

namespace App\Console\Commands;

use App\Models\Node;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class NodeStatuses extends Command
{
    protected $signature = 'nodes:update';

    public function handle(): int
    {
        /** @var Collection<Node> $nodes */
        $nodes = Node::where('reputation', '>=', 0)->get();

        foreach ($nodes as $node) {
            $this->info('Updating status of ' . $node->node_id . '...');

            $node->updateStatus();
        };

        $this->info('Node statuses updated successfully.');

        return 0;
    }
}
