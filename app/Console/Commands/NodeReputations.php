<?php

namespace App\Console\Commands;

use App\Models\LedgerRecord;
use App\Models\LedgerType;
use App\Models\Node;
use Illuminate\Console\Command;

class NodeReputations extends Command
{
    protected $signature = 'nodes:reputations';

    public function handle(): int
    {
        $nodes = Node::where('reputation', '>', 0)->get();

        $reputationLedgerTypeId = LedgerType::firstWhere('name', 'reputation')->id;

        $nodes->each(function ($node) use ($reputationLedgerTypeId) {
            LedgerRecord::create([
                'ledger_type_id' => $reputationLedgerTypeId,
                'node_id' => $node->id,
                'value' => $node->reputation,
            ]);
        });

        return 0;
    }
}
