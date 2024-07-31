<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class NSApiService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = env('NS_API_KEY');
        $this->client = new Client();
    }

    public function getStations()
    {
        $cacheKey = 'ns_stations';

        $stations = Cache::remember($cacheKey, 15 * 60, function () {
            $url = "https://gateway.apiportal.ns.nl/nsapp-stations/v2";

            $response = $this->client->request('GET', $url, [
                'headers' => [
                    'Ocp-Apim-Subscription-Key' => $this->apiKey,
                ]
            ]);

            return json_decode($response->getBody()->getContents(), true)['payload'] ?? [];
        });

        return $stations;
    }

    public function getTrainSchedule($request, $page, $perPage)
    {
        $stationCode = $request['station'] ?? 'RAI';
        $type = $request['type'] ?? 'arrivals';

        $cacheKey = "{$stationCode}_{$type}_schedule";

        $schedules = Cache::remember($cacheKey, 15 * 60, function () use ($stationCode, $type) {
            $url = "https://gateway.apiportal.ns.nl/reisinformatie-api/api/v2/{$type}";

            $response = $this->client->request('GET', $url, [
                'headers' => [
                    'Ocp-Apim-Subscription-Key' => $this->apiKey,
                ],
                'query' => [
                    'station' => $stationCode,
                ]
            ]);

            return json_decode($response->getBody()->getContents(), true)['payload'][$type] ?? [];
        });

        $offset = ($page - 1) * $perPage;
        $itemsForCurrentPage = array_slice($schedules, $offset, $perPage);

        return new LengthAwarePaginator($itemsForCurrentPage, count($schedules), $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
            'query' => $request->query()
        ]);
    }

    public function getDisruptions($stationCode)
    {
        $cacheKey = "{$stationCode}_disruptions";

        $disruptions = Cache::remember($cacheKey, 15 * 60, function () use ($stationCode) {
            $url = "https://gateway.apiportal.ns.nl/disruptions/v3/station/{$stationCode}";

            $response = $this->client->request('GET', $url, [
                'headers' => [
                    'Ocp-Apim-Subscription-Key' => $this->apiKey,
                ]
            ]);

            return json_decode($response->getBody()->getContents(), true) ?? [];
        });

        return $disruptions;
    }
}
