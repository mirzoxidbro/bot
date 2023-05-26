<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramBotController extends Controller
{
    public function handle(Request $request)
    {
        $update = Telegram::commandsHandler(true);
        $message = $update->getMessage();
        $text = $message->getText();

        if ($text == '/start') {
            $this->startCommand($message);
        } elseif ($text == '/help') {
            $this->helpCommand($message);
        } elseif ($text == '/hello') {
            $this->helloCommand($message);
        } else {
            $this->fallbackCommand($message);
        }
    }

    private function startCommand($message)
    {
        $chatId = $message->getChat()->getId();
        $response = Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => 'Salom botga xush kelibsiz!',
        ]);
    }

    private function helpCommand($message)
    {
        $chatId = $message->getChat()->getId();
        $response = Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => 'Yordam uchun xabar!',
        ]);
    }

    private function helloCommand($message)
    {
        $chatId = $message->getChat()->getId();
        $response = Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => 'Salom! Sizga qanday yordam berishim mumkin?',
        ]);
    }

    private function fallbackCommand($message)
    {
        $chatId = $message->getChat()->getId();
        $response = Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => 'Noto\'g\'ri buyruq! Iltimos, to\'g\'ri buyruqlardan foydalaning.',
        ]);
    }
}

