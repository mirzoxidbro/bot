<?php

namespace App\Services;

class FirstBotService {

    public $telegram;
    public function __construct()
    { 
        $this->telegram = new Telegram('6184404394:AAF2Hv4XDZKw37rROZKRJP9Nq1hagBs7y4E');
    }

    public function handle()
    {
        $chat_id = $this->telegram->ChatID();
        $text = $this->telegram->Text();
        // info($text);
        $data = $this->telegram->getData();
        $message = $data['message'];
        // $text = $message['text'];
        if(isset($data['message']['contact'])){
            info($data['message']['contact']['phone_number']);
        }
        $this->telegram->sendMessage([
            'chat_id' => $chat_id,
            'text' => json_encode($data, JSON_PRETTY_PRINT)
        ]);
        if($text == '/start'){ 
            $content = array('chat_id' => $chat_id, 'text' => "Salom botimizga xush kelibsiz");
            $this->telegram->sendMessage($content);

            $option = [
                [$this->telegram->buildKeyboardButton("Ma'lumot olish")], 
                [$this->telegram->buildKeyboardButton("Buyurtma berish")] 
            ];
            $keyb = $this->telegram->buildKeyBoard($option, $onetime=false);
            $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "This is a Keyboard Test");
            $this->telegram->sendMessage($content);
        }elseif($text == 'Buyurtma berish'){
            $option = [
                [$this->telegram->buildKeyboardButton("1 kg")], 
                [$this->telegram->buildKeyboardButton("2 kg")] 
            ];
            $keyb = $this->telegram->buildKeyBoard($option, $onetime=false);
            $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "Buyurtma berish uchun hajmlardan birini tanlang");
            $this->telegram->sendMessage($content);

        }elseif($text == "Ma'lumot olish"){
            $content = array('chat_id' => $chat_id, 'text' => "Vapshe zo'r");
            $this->telegram->sendMessage($content);
        }elseif($text == "1 kg"){
            $this->askContact();
        }elseif($text == "2 kg"){
            $this->askContact();
        }
    }

    private function askContact()
    {
        $chat_id = $this->telegram->ChatID();

        $option = [
            [$this->telegram->buildKeyboardButton("Kontraktni ulashish", $request_contact = true)],
        ];
        $keyb = $this->telegram->buildKeyBoard($option, $onetime=true);
        $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "Iltimos kontaktingizni jo'nating");
        $this->telegram->sendMessage($content);        
    }

}