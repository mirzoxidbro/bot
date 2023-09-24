<?php

namespace App\Services;

class TrainingCenterBotService
{
    public $pagination;
    public $telegram;
    public array $orderTypes;
    public $textServices;
    public $districts;

    public function __construct()
    {
        $this->pagination = new Pagination();
        $this->telegram = new Telegram('6184404394:AAF2Hv4XDZKw37rROZKRJP9Nq1hagBs7y4E');
        $this->textServices = new TextServices();
        $this->districts = new DistrictsService();
    }

    public function handle()
    {
        $data = $this->telegram->getData();
        $message = $data['message'];
        $text = $message['text'];
        $chat_id = $message['chat']['id'];
        info($this->pagination->getPage($chat_id));
        if($text == '/start')
        {
            $this->setLanguage($chat_id);
        }else{
            switch($this->pagination->getPage($chat_id)){
                case 'start':
                    if($text == "🇺🇿 O'zbek tili"){
                        $this->pagination->setLanguage($chat_id, 'uz');
                    }elseif($text == "🇷🇺 Pусский язык"){
                        $this->pagination->setLanguage($chat_id, 'ru');
                    }else{
                        $this->chooseButton(@$chat_id);
                    }
                    break;
                case 'main':
                    switch($text){
                        case $this->textServices->getText('choose_category', @$chat_id) :
                            $this->showDistricts(@$chat_id);
                            break;
                        case "💎 ". $this->textServices->getText('list_training_centers', @$chat_id);

                            break;

                        case '🇺🇿 🇷🇺'.$this->textServices->getText('change_lang', $chat_id):
                            $this->changeLang($chat_id);
                            break;
                        default:
                            $this->showMainPage($chat_id);
                            break;
                    }
                case 'change_lang':
                    if($text == "🇺🇿 O'zbek tili"){
                        $this->pagination->setLanguage($chat_id, 'uz');
                    }elseif($text == "🇷🇺 Pусский язык"){
                        $this->pagination->setLanguage($chat_id, 'ru');
                    }else{
                        $this->changeLang($chat_id);
                    }
                    break;
            }
        }
    }

    private function setLanguage($chat_id)
    {
        $this->pagination->setPage($chat_id, 'main');
        $option = [
            [$this->telegram->buildKeyboardButton("🇺🇿 O'zbek tili"), $this->telegram->buildKeyboardButton("🇷🇺 Pусский язык")]
        ];
        $keyb = $this->telegram->buildKeyBoard($option, $onetime=false, $resize=true);
        $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "Iltimos tilni tanlang. \nПожалуйста, выберите язык");
        $this->telegram->sendMessage($content);
    }

    private function showMainPage($chat_id)
    {
        $this->pagination->setPage($chat_id, 'main');

        $option = [
            [$this->telegram->buildKeyboardButton($this->textServices->getText('choose_category', $chat_id)),$this->telegram->buildKeyboardButton("💎 ".$this->textServices->getText('list_training_centers', $chat_id))],
            [$this->telegram->buildKeyboardButton('🇺🇿 🇷🇺'.$this->textServices->getText('change_lang', $chat_id))]
        ];
        $keyb = $this->telegram->buildKeyBoard($option, $onetime=false, $resize=true);
        $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "Iltimos tilni tanlang. \nПожалуйста, выберите язык");
        $this->telegram->sendMessage($content);
        
    }

    private function chooseButton($chat_id)
    {
        $content = array('chat_id' => $chat_id, 'disable_web_page_preview' => false,'text' => "Iltimos quyidagi tugmalardan birini tanlang");
        $this->telegram->sendMessage($content);
    }

    private function showDistricts($chat_id)
    {
        $this->pagination->setPage($chat_id, 'district');
        $text = $this->textServices->getText('choose_district', $chat_id);
        foreach($this->districts->getDistrict($chat_id) as $district){
            $option[] = [$this->telegram->buildKeyboardButton("📍 ". $district)];
        }
        $keyb = $this->telegram->buildKeyBoard($option, $onetime=false, $resize=true);
        $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => $text);
        $this->telegram->sendMessage($content);
    }

    private function changeLang($chat_id)
    {
        $this->pagination->setPage($chat_id, 'change_lang');

        $option = [
            [$this->telegram->buildKeyboardButton("🇺🇿 O'zbek tili"), $this->telegram->buildKeyboardButton("🇷🇺 Pусский язык")]
        ];
        $keyb = $this->telegram->buildKeyBoard($option, $onetime=false, $resize=true);
        $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "Iltimos tilni tanlang. \nПожалуйста, выберите язык");
        $this->telegram->sendMessage($content);
    }

}   