<?php

namespace App\Services;

use App\Models\Address;
use App\Models\Country;
use App\Models\Node;
use App\Models\Protocol;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class Internxt
{
    public function updateNodes(int $page = 1): Response
    {
        $response = $this->prepareRequest()->get('contacts', ['page' => $page]);

        if ($response->failed()) {
            Log::error('Internxt API call failed.', [
                'status' => $response->status(),
                'response' => $response->body(),
            ]);

            return $response;
        }

        if (($nodes = collect($response->json()))->count() > 0) {
            foreach ($nodes as $node) {
                $this->store($node);
            }

            $this->updateNodes(++$page);
        }

        return $response;
    }

    protected function prepareRequest(): PendingRequest
    {
        return Http::baseUrl(config('services.internxt.base_url'))
            ->asJson()
            ->acceptJson();
    }

    protected function store(array $data): bool
    {
        $validator = Validator::make(
            ['ip' => $ip = Arr::get($data, 'ip')],
            ['ip' => 'required|ip'],
        );

        if ($validator->fails()) {
            return false;
        }

        /** @var Node $node */
        $node = Node::withTrashed()->firstOrNew(['node_id' => Arr::get($data, 'nodeID')]);

        if ($node->trashed()) {
            $node->restore();
        }

        /** @var Address $address */
        $address = Address::firstOrCreate(['ip' => $ip]);

        if ($address->wasRecentlyCreated) {
            $ipStackData = resolve(IPStack::class)
                ->getLocationByIpAddress($ip)
                ->json();

            if ($countryName = Arr::get($ipStackData, 'country_name')) {
                $country = Country::firstOrCreate(
                    ['name' => $countryName],
                    ['code' => Arr::get($ipStackData, 'country_code')],
                );

                $address->update(['country_id' => $country->id]);
            }
        }

        $node->fill([
            'ip' => Arr::get($data, 'ip'),
            'port' => Arr::get($data, 'port'),
            'user_agent' => Arr::get($data, 'userAgent'),
            'reputation' => Arr::get($data, 'reputation'),
            'timeout_rate' => Arr::get($data, 'timeoutRate'),
            'response_time' => Arr::get($data, 'responseTime'),
            'space_available' => Arr::get($data, 'spaceAvailable'),
            'last_seen' => Carbon::createFromTimeString(Arr::get($data, 'lastSeen', '')),
            'protocol_id' => Protocol::firstOrCreate(['name' => Arr::get($data, 'protocol')])->id,
            'address_id' => $address->id,
        ])->save();

        $address->recalculateReputation();

        return true;
    }
}
