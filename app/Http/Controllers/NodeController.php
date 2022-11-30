<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Country;
use App\Models\LedgerRecord;
use App\Models\LedgerType;
use App\Models\Node;
use App\Models\Protocol;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View as ViewFacade;

// use App\Services\API\InternXT;
// use App\Services\API\IPStack;
// use App\Services\Ledger;

class NodeController extends Controller
{
    public function index(): View
    {
        $nodes = Node::all()->sort(function ($a, $b) {
            return $b->reputation === $a->reputation
                ? $a->country > $b->country
                : $b->reputation > $a->reputation;
        });

        $rank = $lastReputation = 0;

        $nodes->each(function ($node) use (&$rank, &$lastReputation) {
            $rank++;

            if ($lastReputation != $node->reputation) {
                $lastReputation = $node->reputation;
                $node->rank = $rank;
            }
        });

        return ViewFacade::make('nodes', [
            'nodes' => $nodes,
        ]);
    }

    public function show(string $nodeId): View
    {
        /** @var Node $node */
        $node = Node::where('node_id', $nodeId)->firstOrFail();

        $reputations = [];

        LedgerRecord::query()
            ->where('ledger_type_id', LedgerType::firstWhere('name', 'reputation')->id)
            ->where('node_id', $node->id)
            ->limit(14)
            ->orderBy('created_at', 'desc')
            ->get()
            ->reverse()
            ->each(function ($record) use (&$reputations) {
                $reputations['values'][] = $record->value;
                $reputations['dates'][] = $record->created_at->diffForHumans();
            });

        $statuses = [
            'up' => $this->getUpDownStatusCount($node->id),
            'down' => $this->getUpDownStatusCount($node->id, false),
        ];

        $filteredStatuses = collect();

        $ledgerStatusRecords = LedgerRecord::query()
            ->where('ledger_type_id', LedgerType::firstWhere('name', 'status')->id)
            ->where('node_id', $node->id)
            ->limit(12)
            ->get();

        foreach ($ledgerStatusRecords as $ledgerStatusRecord) {
            if ($filteredStatuses->last()?->value === $ledgerStatusRecord->value) {
                continue;
            }

            $filteredStatuses->push($ledgerStatusRecord);
        }

        $filteredStatuses = $filteredStatuses->reverse()->values();

        $statusLogs = collect();

        foreach ($filteredStatuses as $key => $filteredStatus) {
            if ($key === $filteredStatuses->count() - 1) {
                if (($timeDifference = Carbon::now()->diffInHours($filteredStatus->created_at)) !== 0) {
                    $statusLogs->push(sprintf(
                        '<span class="status %s"></span> The node has been %s the last %s hours.',
                        $filteredStatus->value ? 'status-on' : 'status-off',
                        $filteredStatus->value ? 'online' : 'offline',
                        $timeDifference,
                    ));
                } else {
                    $statusLogs->push(sprintf(
                        '<span class="status %s"></span> The node is currently %s.',
                        $filteredStatus->value ? 'status-on' : 'status-off',
                        $filteredStatus->value ? 'online' : 'offline',
                    ));
                }
            } else {
                $statusLogs->push(sprintf(
                    '<span class="status %s"></span> The node was %s %s hours ago.',
                    $filteredStatus->value ? 'status-on' : 'status-off',
                    $filteredStatus->value ? 'online' : 'offline',
                    Carbon::now()->diffInHours($filteredStatus->created_at),
                ));
            }

            if ($key + 1 === $filteredStatuses->count()) {
                break;
            }

            $statusLogs->push(
                sprintf(
                    'For %s hours',
                    $filteredStatus->created_at->diffInHours($filteredStatuses[$key + 1]->created_at)
                )
            );
        }

        return ViewFacade::make('node', [
            'node' => $node,
            'statuses' => $statuses,
            'reputations' => $reputations,
            'statusLogs' => $statusLogs->take(-7),
        ]);
    }

    private function getUpDownStatusCount(int $nodeId, bool $up = true)
    {
        return DB::table('ledger_records')
            ->select(DB::raw('count(value) as count'))
            ->where('node_id', $nodeId)
            ->where('ledger_type_id', LedgerType::firstWhere('name', 'status')->id)
            ->where('value', $up ? 1 : 0)
            ->whereRaw('created_at BETWEEN date_sub(now(), INTERVAL 1 WEEK) and now()')
            ->value('count');
    }
}
