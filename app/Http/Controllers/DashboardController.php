<?php

namespace App\Http\Controllers;

use App\Services\NSApiService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $service;

    public function __construct(NSApiService $NSApiService)
    {
        $this->service = $NSApiService;
    }

    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = 10;

        $stationCode = $request['station'] ?? 'RAI';
        $type = $request['type'] ?? 'arrivals';

        $data["stations"] = $this->service->getStations();
        $data["schedules"] = $this->service->getTrainSchedule($request, $page, $perPage);
        $data["disruptions"] = $this->service->getDisruptions($stationCode);
        $data["station"] = $stationCode;
        $data["type"] = $type;

        return view('dashboard', $data);
    }
}
