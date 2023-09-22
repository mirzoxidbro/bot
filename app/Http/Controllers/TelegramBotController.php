<?php

namespace App\Http\Controllers;

use App\Services\FirstBotService;
use App\Services\Telegram;
use Illuminate\Http\Request;

class TelegramBotController extends Controller
{
    public function __construct(public FirstBotService $service)
    {
        
    }
    public function handle(Request $request)
    {
        return $this->service->handle();
    }
}
