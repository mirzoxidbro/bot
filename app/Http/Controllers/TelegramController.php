<?php

namespace App\Http\Controllers;

use App\Models\BotUser;
use GuzzleHttp\Client;

class TelegramController extends Controller
{
    public function handle()
    {
        $update = json_decode(file_get_contents('php://input'), true);
        $chat_id = $update['message']['chat']['id'];
        $text = $update['message']['text'];

        $user = BotUser::firstOrNew(['chat_id' => $chat_id]);

        if ($text == '/start') {
            $reply = 'Iltimos, foydalanuvchi nomingizni kiriting:';
            $user->state = 'username';
        } else {
            if ($user->state == 'username') {
                $user->username = $text;
                $reply = 'Iltimos, telefon raqamingizni kiriting:';
                $user->state = 'phone';
            } elseif ($user->state == 'phone') {
                $user->phone = $text;
                $reply = 'Iltimos, parolingizni kiriting:';
                $user->state = 'password';
            } elseif ($user->state == 'password') {
                $user->password = bcrypt($text);
                $reply = 'Ro\'yxatdan o\'tish muvaffaqiyatli yakunlandi!';
                $user->state = '';

                $keyboard = [
                    'inline_keyboard' => [
                        [
                            ['text' => 'Tasdiqlash', 'callback_data' => 'confirm']
                        ]
                    ]
                ];
                $reply_markup = json_encode($keyboard);

                $client = new Client();
                $response = $client->post(env('BASE_URL'), [
                    'form_params' => [
                        'chat_id' => $chat_id,
                        'text' => $reply,
                        'reply_markup' => $reply_markup
                    ]
                ]);
            } else {
                if ($update['callback_query']['data'] == 'confirm') {
                    $reply = "Ma'lumotlar saqlandi!";
                } else {
                    $reply = "Nimadir noto'g'ri ketdi. Iltimos /start buyrug'ini bosing.";
                    $user->state = '';
                }
            }
        }

        $user->save();

        $keyboard = [
            'keyboard' => [
                [
                    ['text' => $reply]
                ]
            ],
            'resize_keyboard' => true,
            'one_time_keyboard' => true
        ];
        $reply_markup = json_encode($keyboard);

        $client = new Client();
        $response = $client->post(env('BASE_URL'), [
            'form_params' => [
                'chat_id' => $chat_id,
                'text' => 'hello world',
                'reply_markup' => $reply_markup
            ]
        ]);
        return 'ok';
    }

}
