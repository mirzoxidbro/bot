<?php

namespace App\Services;

class MyBot
{
    public $pagination;
    public $telegram;
    public array $orderTypes;

    public function __construct()
    {
        $this->pagination = new Pagination();
        $this->telegram = new Telegram('6184404394:AAF2Hv4XDZKw37rROZKRJP9Nq1hagBs7y4E');
        $this->orderTypes = ['1kg - 50 000', '1.5kg - 750 000', '4.5kg - 450 000'];
    }

    public function handle()
    {
        $data = $this->telegram->getData();
        
        
        $message = $data['message'];
        $text = $message['text'];
        $chat_id = $message['chat']['id'];
        if($text == '/start'){
            $this->showMain();
        }else{
            switch ($this->pagination->getPage($chat_id)){
                case 'main':
                if($text == "Batafsil ma'lumot"){
                    $this->showAbout($chat_id);
                }else if($text == "Buyurtma berish"){
                    $this->showOrder($chat_id);
                }else{
                    $this->chooseButton($chat_id);
                }
                break;
                case 'massa':
                    if(in_array($text, $this->orderTypes)){
                        $this->askContact(@$chat_id);
                    }elseif($text == 'orqaga')
                    {
                        $this->showMain();
                    }
                    else{
                        $this->chooseButton(@$chat_id);
                    }
                break;
                case 'contact':
                    info(isset($this->telegram->getData()['contact']['phone']));
                    info($this->telegram->getData());
                    if(isset($text['contact']['phone'])){
                        $this->acceptContact($chat_id);
                    }elseif($text == 'orqaga'){
                        $this->showOrder($chat_id);
                    }else{
                        $content = array('chat_id' => $chat_id, 'disable_web_page_preview' => false,'text' => "Iltimos bot orqali raqam qoldiring!");
                        $this->telegram->sendMessage($content);
                    }
                    break;
            }
        }
    }

    public function showMain()
    {
        $data = $this->telegram->getData();
        $message = $data['message'];
        $text = $message['text'];
        $chat_id = $message['chat']['id'];
        $this->pagination->setPage($chat_id, 'main');

        $option = [
            [$this->telegram->buildKeyboardButton("Batafsil ma'lumot")],
            [$this->telegram->buildKeyboardButton("Buyurtma berish")],
        ];
        $keyb = $this->telegram->buildKeyBoard($option, $onetime=false, $resize=true);
        $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "Assalomu alaykum text 1");
        $this->telegram->sendMessage($content);

        $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'disable_web_page_preview' => false,'text' => "Batafsil ma'lumot text 2");
        $this->telegram->sendMessage($content);
    }

    private function chooseButton($chat_id)
    {
        $content = array('chat_id' => $chat_id, 'disable_web_page_preview' => false,'text' => "Iltimos quyidagi tugmalardan birini tanlang");
        $this->telegram->sendMessage($content);
    }

    private function showAbout($chat_id)
    {
        $content = array('chat_id' => $chat_id, 'disable_web_page_preview' => false,'text' => "Bu yerda batafsil ma'lumot bo'ladi");
        $this->telegram->sendMessage($content);
    }

    private function showOrder($chat_id)
    {
        $this->pagination->setPage($chat_id, 'massa');
        $option = [
            [$this->telegram->buildKeyboardButton("1kg - 50 000")],
            [$this->telegram->buildKeyboardButton("1.5kg - 750 000")],
            [$this->telegram->buildKeyboardButton("4.5kg - 450 000")],
            [$this->telegram->buildKeyboardButton("orqaga")],
        ];

        $keyb = $this->telegram->buildKeyBoard($option, $onetime=false, $resize=true);
        $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "Buyurtma berish uchun hajmlardan birini tanlang");
        $this->telegram->sendMessage($content);
    }

    private function askContact($chat_id)
    {
        $this->pagination->setPage($chat_id, 'contact');
        $option = [
            [$this->telegram->buildKeyboardButton("Kontraktni ulashish")],
            [$this->telegram->buildKeyboardButton("orqaga")],
        ];
        $keyb = $this->telegram->buildKeyBoard($option, $onetime=false, $resize=true);
        $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "Iltimos kontaktingizni jo'nating");
        $this->telegram->sendMessage($content);
    }

    private function acceptContact($chat_id)
    {
        $this->pagination->setPage($chat_id, 'accept');
        $content = array('chat_id' => $chat_id, 'disable_web_page_preview' => false,'text' => "Buyurtmangiz qabul qilindi. Tez orada siz bilan bog'lanamiz");
        $this->telegram->sendMessage($content);
    }

}