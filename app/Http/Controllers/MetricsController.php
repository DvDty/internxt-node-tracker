<?php

namespace App\Http\Controllers;

use App\Enums\MetricType;
use App\Models\LedgerType;
use App\Models\Node;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View as ViewFacade;

class MetricsController extends Controller
{
    public function home(): View
    {
        return ViewFacade::make('home', [
            'mostReputationGained' => $this->getMostReputationGained(),
            'mostReputationLost' => $this->getMostReputationLost(),
            'countryDistribution' => $this->getCountryDistributions(),
        ]);
    }

    protected function getMostReputationGained()
    {
        return $this->getMostReputationQuery(MetricType::ReputationGained);
    }

    protected function getMostReputationLost()
    {
        return $this->getMostReputationQuery(MetricType::ReputationLost);
    }

    protected function getMostReputationQuery(MetricType $metricType)
    {
        $algorithm = match ($column = $metricType->value) {
            MetricType::ReputationLost->value => '(ledger_records.value - nodes.reputation)',
            MetricType::ReputationGained->value => '(nodes.reputation - ledger_records.value)',
        };

        $ledgerTypeId = LedgerType::firstWhere('name', 'reputation')->id;

        $diff = DB::table('ledger_records')
            ->joinSub($this->getTimeLimitQuery($ledgerTypeId), 'time_limit', function ($join) {
                $join->on('time_limit.node_id', '=', 'ledger_records.node_id');
                $join->on('time_limit.latest', '=', 'ledger_records.created_at');
            })
            ->join('nodes', 'ledger_records.node_id', '=', 'nodes.id')
            ->select('ledger_records.node_id', DB::raw("$algorithm as $column"))
            ->where('ledger_type_id', '=', $ledgerTypeId)
            ->having($column, '>', 0)
            ->orderByDesc($column)
            ->limit(5)
            ->get();

        return Node::find($diff->pluck('node_id')->toArray())
            ->each(fn($node) => $node->$column = $diff->where('node_id', $node->id)->first()->$column)
            ->sortByDesc($column)
            ->values();
    }

    protected function getTimeLimitQuery(int $ledgerTypeId): Builder
    {
        return DB::table('ledger_records')
            ->select('node_id', DB::raw('MIN(created_at) AS latest'))
            ->where('created_at', '>', DB::raw('DATE_SUB(NOW(), INTERVAL 48 HOUR)'))
            ->where('ledger_type_id', $ledgerTypeId)
            ->groupBy('node_id');
    }

    protected function getCountryDistributions(): array
    {
        $distributions = DB::table('addresses')
            ->join('countries', 'addresses.country_id', '=', 'countries.id')
            ->select('name', DB::raw('sum(reputation) as total'))
            ->groupBy('name')
            ->orderByDesc('total')
            ->limit($limit = 10)
            ->get()
            ->toArray();

        array_unshift($distributions, [
            'name' => 'Other',
            'total' => DB::table('addresses')
                ->select('reputation')
                ->orderByDesc('reputation')
                ->get()
                ->pluck('reputation')
                ->splice($limit)
                ->sum(),
        ]);

        return [
            'countries' => array_column($distributions, 'name'),
            'reputations' => array_column($distributions, 'total'),
        ];
    }
}
