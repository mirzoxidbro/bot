<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram\Bot\Api;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Keyboard\Keyboard;


class BotController extends Controller
{
    public function __construct(public Api $telegram)
    {
    }


    public function show()
    {
        $response = $this->telegram->sendMessage([
            'chat_id' => '5798866690',
            'text' => 'Wassup'
        ]);

        $this->telegram->sendChatAction([
            'chat_id' => '5798866690',
            'action' => 'upload_photo'
        ]);
        
        // return $response;
    }

    public function keyboard()
    {
        $keyboard = [
            ['7', '8', '9'],
            ['4', '5', '6'],
            ['1', '2', '3'],
            ['0']
        ];
    
        $reply_markup = Keyboard::make([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => true
        ]);
    
        $response = Telegram::sendMessage([
            'chat_id' => '5798866690',
            'text' => 'Hello World',
            'reply_markup' => $reply_markup
        ]);
    
    }
    
}



// 5798866690