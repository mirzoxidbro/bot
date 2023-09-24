<?php

namespace App\Http\Controllers;

use App\Services\TrainingCenterBotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrainingCentreBotController extends Controller
{
    public function __construct(public TrainingCenterBotService $service)
    {
    }

    public function handle()
    {
        $this->service->handle();
    }
}
