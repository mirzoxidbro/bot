<?php

namespace App\Services;

class FirstBotService extends Telegram{

    public function __construct()
    {
        $this->token = '6184404394:AAF2Hv4XDZKw37rROZKRJP9Nq1hagBs7y4E';
    }

    public function handle()
    {
        $telegram = new Telegram('6184404394:AAF2Hv4XDZKw37rROZKRJP9Nq1hagBs7y4E');
        $chat_id = $telegram->ChatID();
        $text = $telegram->Text();
    
        if($text == '/start'){ 
            $option = [
                array($telegram->buildKeyboardButton("Button 1"), $telegram->buildKeyboardButton("Button 2")), 
                array($telegram->buildKeyboardButton("Button 3"), $telegram->buildKeyboardButton("Button 4")), 
                array($telegram->buildKeyboardButton("Button 6")) 
            ];
            $keyb = $telegram->buildKeyBoard($option, $onetime=false);
            $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "This is a Keyboard Test");
            $telegram->sendMessage($content);
        }else{
            $content = array('chat_id' => $chat_id, 'text' => "<a href='https://www.bing.com/search?q=bing+ai&form=ANSPH1&refig=b39ab3a84e5543e5abc13ad47f43fe1f&pc=U531'>link test</a>", 'parse_mode' => 'html');
            $telegram->sendMessage($content);
        }
    }

}