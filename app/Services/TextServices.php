<?php

namespace App\Services;

use App\Models\BotTexts;

class TextServices
{
    public $service;
    public function __construct()
    {
        $this->service = new Pagination();
    }

    public function getText($keyword, $chat_id)
    {
        $lang = $this->service->getLanguage($chat_id);

        $text = BotTexts::where('keyword', $keyword)->first();
        if($text){
            return $text->$lang;
        }
        return '';
    }

}