<?php

namespace App\Services;


class VacancyService
{

    public $pagination;
    public $telegram;
    public $texts;

    public function __construct()
    {
        $this->pagination = new Pagination();
        $this->telegram = new Telegram('6184404394:AAF2Hv4XDZKw37rROZKRJP9Nq1hagBs7y4E');
        $this->texts = new TextServices();
    }

    public function handle()
    {
        $data = $this->telegram->getData();
        $message = $data['message'];
        $text = $message['text'];
        $chat_id = $message['chat']['id'];
        if($text == '/start'){
            // $this->setLanguage($chat_id);
            $this->showMain($chat_id);
        }else{
            switch($this->pagination->getPage($chat_id)){
                case 'start':
                    if($text == "🇺🇿 O'zbek tili"){
                        $this->pagination->setLanguage($chat_id, 'uz');
                    }elseif($text == "🇷🇺 Pусский язык"){
                        $this->pagination->setLanguage($chat_id, 'ru');
                    }else{
                        $this->chooseButton($chat_id);
                    }
                    break;

                case 'main':
                    if($text == "🇺🇿🔄🇷🇺 " . $this->texts->getText('change_lang', $chat_id)){
                        if($text == "🇺🇿🔄🇷🇺 Tilni o'zgartirish"){
                            $this->pagination->setLanguage($chat_id, 'ru');
                        }elseif($text == "🇺🇿🔄🇷🇺 Изменить язык")
                        {
                            $this->pagination->setLanguage($chat_id, 'uz');
                        }
                        $this->showMain($chat_id);
                    }
                    break;
            }
        }

    }


    private function showMain($chat_id)
    {
        $this->pagination->setPage($chat_id, 'main');
        $option = [
            [$this->telegram->buildKeyboardButton("🏢 ". $this->texts->getText('about_us', @$chat_id))],
            [$this->telegram->buildKeyboardButton("💼 ". $this->texts->getText('vacancies', @$chat_id))],
            [$this->telegram->buildKeyboardButton("🔥 ". $this->texts->getText('hot_vacansies', @$chat_id))],
            [$this->telegram->buildKeyboardButton("💬 ". $this->texts->getText('callback', @$chat_id))],
            [$this->telegram->buildKeyboardButton("📞 ". $this->texts->getText('contacts', @$chat_id)), $this->telegram->buildKeyboardButton("🇺🇿🔄🇷🇺 ". $this->texts->getText('change_lang', @$chat_id))],

        ];
        $keyb = $this->telegram->buildKeyBoard($option, $onetime=false, $resize=true);
        $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "Телеграм бот-сайт Ish.uz");
        $this->telegram->sendMessage($content);
    }

    private function setLanguage($chat_id)
    {
        $this->pagination->setPage($chat_id, 'start');
        $option = [
            [$this->telegram->buildKeyboardButton("🇺🇿 O'zbek tili"), $this->telegram->buildKeyboardButton("🇷🇺 Pусский язык")]
        ];
        $keyb = $this->telegram->buildKeyBoard($option, $onetime=false, $resize=true);
        $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "Iltimos tilni tanlang. \nПожалуйста, выберите язык");
        $this->telegram->sendMessage($content);
    }

    private function chooseButton($chat_id)
    {
        $content = array('chat_id' => $chat_id, 'disable_web_page_preview' => false,'text' => "Iltimos belgilangan tugmalardan birini tanlang");
        $this->telegram->sendMessage($content);
    }

}