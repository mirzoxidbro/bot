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
                        $this->chooseButton($chat_id);
                    }
                    break;
                case 'main':
                    switch($text){
                        case $this->textServices->getText('choose_training_center', $chat_id):
                            $this->showDistricts($chat_id);
                            break;
                        case "💎 ". $this->textServices->getText('list_training_centers', $chat_id);

                            break;

                        case '🇺🇿🔄🇷🇺'.$this->textServices->getText('change_lang', $chat_id):
                            if ($this->pagination->getLanguage($chat_id) == 'ru') {
                                $this->pagination->setLanguage($chat_id, 'uz');
                            } else {
                                $this->pagination->setLanguage($chat_id, 'ru');
                            }
                            $this->showMainPage($chat_id);
                            break;
                        default:
                            $this->showMainPage($chat_id);
                            break;
                    }

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
            [$this->telegram->buildKeyboardButton($this->textServices->getText('choose_training_center', $chat_id)),$this->telegram->buildKeyboardButton("💎 ".$this->textServices->getText('list_training_centers', $chat_id))],
            [$this->telegram->buildKeyboardButton('🇺🇿🔄🇷🇺'.$this->textServices->getText('change_lang', $chat_id))]
        ];
        $keyb = $this->telegram->buildKeyBoard($option, $onetime=false, $resize=true);
        $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "Kerakli yo'nalishni tanlang");
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
        $text = $this->textServices->getText('choose_districts', $chat_id);
        $lang = $this->pagination->getLanguage($chat_id);

        foreach($this->districts->getDistrict($chat_id) as $district){
            info($district->$lang);
            $option[] = [$this->telegram->buildKeyboardButton("📍 ". $district->$lang)];
        }
        $keyb = $this->telegram->buildKeyBoard($option, $onetime=false, $resize=true);
        $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => $text);
        $this->telegram->sendMessage($content);
    }

}   