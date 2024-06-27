<?php

namespace App\Http\Controllers\Api\V1;

use App\Contracts\NginxRequestFrequencyRepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NginxController extends Controller
{

    public function writeRequestFrequency(Request $request, NginxRequestFrequencyRepositoryInterface $nginxRequestFrequencyRepository)
    {
        $request->validate([
            'frequency' => 'required|integer'
        ]);
        return response()->json($nginxRequestFrequencyRepository->writeRequestFrequency($request->get('frequency')));
    }

    public function getNginxRequestFrequencyForThePeriod(Request $request, NginxRequestFrequencyRepositoryInterface $nginxRequestFrequencyRepository)
    {
        $request->validate([
            'from' => 'required|date',
            'to' => 'required|date'
        ]);
        return response()->json($nginxRequestFrequencyRepository->getNginxRequestFrequencyForThePeriod($request->get('from'), $request->get('to')));
    }
}
