<?php

namespace App\Http\Controllers;

use App\Services\VacancyService;
use Illuminate\Http\Request;

class VacancyController extends Controller
{
    public function __construct(public VacancyService $service)
    {
    }

    public function handle()
    {
        $this->service->handle();
    }
}
