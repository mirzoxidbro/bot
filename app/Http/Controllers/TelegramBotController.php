<?php

namespace App\Http\Controllers;

use App\Services\FirstBotService;
use App\Services\MyBot;
use App\Services\Telegram;
use Illuminate\Http\Request;

class TelegramBotController extends Controller
{
    public function __construct(public FirstBotService $service, public MyBot $bot)
    {
        
    }
    public function handle(Request $request)
    {
        // return $this->service->handle();
        return $this->bot->handle();
    }
}
