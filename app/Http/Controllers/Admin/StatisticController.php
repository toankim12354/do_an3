<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\CustomErrorException;
use App\Exports\Excel\StatisticAttendanceExportMultiple;
use App\Http\Controllers\Controller;
use App\Models\Assign;
use App\Models\Grade;
use App\Models\Subject;
use App\Services\StatisticService;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    private StatisticService $service;

    public function __construct(StatisticService $service)
    {
        $this->service = $service;
    }

    public function attendance(Request $request)
    {
        return $this->service->getView($request);
    }

    public function exportExcel(Request $request)
    {
        return $this->service->exportExcel($request);
    }

    public function storeExcel(Request $request)
    {
        return $this->service->storeExcel($request);
    }

    public function sendEmail(Request $request)
    {
        return $this->service->sendEmail($request);
    }
}
