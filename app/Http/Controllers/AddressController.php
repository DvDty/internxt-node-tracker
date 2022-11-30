<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\LedgerRecord;
use App\Models\LedgerType;
use App\Rules\IpAddressMatchesRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View as ViewFacade;
use Symfony\Component\HttpFoundation\Response;

class AddressController extends Controller
{
    public function index(): View
    {
        $addresses = Address::all()->sortByDesc('reputation')->values();

        return ViewFacade::make('addresses', [
            'addresses' => $addresses,
        ]);
    }

    public function show(string $ip): View
    {
        $address = Address::where('ip', $ip)->firstOrFail();

        $reputations = $dates = $nodeShortIds = [];
        $mostRecords = 0;

        LedgerRecord::where('ledger_type_id', LedgerType::firstWhere('name', 'reputation')->id)
            ->whereIn('node_id', $address->nodes->pluck('id')->toArray())
            ->limit($address->numberOfNodes * 7)
            ->orderBy('created_at', 'desc')
            ->get()
            ->reverse()
            ->each(function ($record) use (&$reputations, &$dates) {
                $reputations[$record->node_id][] = $record->value;
                $dates[$record->node_id][] = $record->created_at->diffForHumans();
            });

        $address->nodes->each(function ($node) use (&$nodeShortIds) {
            $nodeShortIds[$node->id] = $node->shortId;
        });

        foreach ($reputations as $reputation) {
            if (($count = count($reputation)) > $mostRecords) {
                $mostRecords = $count;
            }
        }

        foreach ($reputations as &$reputation) {
            if (($count = count($reputation)) < $mostRecords) {
                while (--$count) {
                    array_unshift($reputation, 0);
                }
            }
        }

        foreach ($dates as $date) {
            if (count($date) === $mostRecords) {
                $dates = $date;
                break;
            }
        }

        $chartDataJson = json_encode([
            'reputations' => $reputations,
            'dates' => $dates,
            'nodeShortIds' => $nodeShortIds,
        ]);

        return ViewFacade::make('address', [
            'address' => $address,
            'showEmail' => request()->ip() === $address->ip,
            'chartDataJson' => $chartDataJson,
        ]);
    }

    public function changeEmail(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'addressId' => ['required', new IpAddressMatchesRequest()],
            'email' => 'required|email|max:250',
        ]);

        if ($validator->fails()) {
            return response()->json([
                [
                    'status' => 'error',
                    'message' => $validator->errors(),
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY,
            ]);
        }

        $address = Address::find($request->input('addressId'));
        $address->email = $request->input('email');
        $address->save();

        return response()->json([
            'data' => 'The email was successfully changed.',
        ]);
    }
}
