<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IPStack
{
    public function getLocationByIpAddress(string $ipAddress): Response
    {
        $response = $this->prepareRequest()->get(trim($ipAddress), [
            'access_key' => config('services.ip_stack.api_key'),
        ]);

        if ($response->failed()) {
            Log::error('IP Stack API call failed.', [
                'status' => $response->status(),
                'response' => $response->body(),
            ]);
        }

        return $response;
    }

    protected function prepareRequest(): PendingRequest
    {
        return Http::baseUrl(config('services.ip_stack.base_url'))
            ->asJson()
            ->acceptJson();
    }
}
