<?php

namespace App\Services;

use App\Models\BotUser;

class Pagination{

    public function setPage($chat_id, $page)
    {
        $user =  BotUser::firstOrCreate([
            'chatId' => $chat_id
        ]);

        $user->step = $page;
        $user->save();
    }

    public function getPage($chat_id)
    {
        $user =  BotUser::firstOrCreate([
            'chatId' => $chat_id
        ]);

        return $user->step;
    }

    public function setLanguage($chat_id, $lang)
    {
        info($lang);
        $user =  BotUser::firstOrCreate([
            'chatId' => $chat_id
        ]);

        $user->lang = $lang;
        $user->save();
    }

    public function getLanguage($chat_id)
    {
        $user =  BotUser::firstOrCreate([
            'chatId' => $chat_id
        ]);

        return $user->lang;
    }

}