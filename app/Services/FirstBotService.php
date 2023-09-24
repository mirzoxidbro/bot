<?php

namespace App\Services;

use App\Models\BotUser;

class FirstBotService {

    public $telegram;
    public $chat_id;
    public function __construct()
    { 
        $this->telegram = new Telegram('6184404394:AAF2Hv4XDZKw37rROZKRJP9Nq1hagBs7y4E');
        $this->chat_id = $this->telegram->ChatID();
    }

    public function handle()
    {
        $user = BotUser::firstOrCreate(
            ['chatId' => $this->chat_id]
        );
        $chat_id = $this->telegram->ChatID();
        $text = $this->telegram->Text();
        $data = $this->telegram->getData();
        $message = $data['message'];

        info($user->step);
        info($text);
        if($text == '/start'){
            $this->showStart();
        }elseif($user->step == BotUser::STEP_ASK_FULLNAME){
            $this->askFullname();
        }elseif($user->step == BotUser::STEP_ASK_FULLNAME){
            $this->askAge();
        }elseif($user->step == BotUser::STEP_ASK_AGE){
            $this->askAge();
        }
    }
 
    private function askContact()
    {
        $option = [
            [$this->telegram->buildKeyboardButton("Kontraktni ulashish", $request_contact = true)],
        ];
        $keyb = $this->telegram->buildKeyBoard($option, $onetime=true, $resize=true);
        $content = array('chat_id' => $this->chat_id, 'reply_markup' => $keyb, 'text' => "Iltimos kontaktingizni jo'nating");
        $this->telegram->sendMessage($content);
    }

    private function askFullname()
    {
        $user = BotUser::where('chatId', $this->chat_id)->first();
        $user->step = BotUser::STEP_ASK_FULLNAME;
        $user->fullname = $this->telegram->Text();
        $user->save();
        $option = [
            [$this->telegram->buildKeyboardButton("Iltimos ismingizni kiriting")]
        ];
        $keyb = $this->telegram->buildKeyBoard($option, $onetime=false,$resize=true);
        $content = array('chat_id' => $this->chat_id, 'reply_markup' => $keyb, 'text' => "Iltimos yoshingizni kiriting");
        $this->telegram->sendMessage($content);
    }

    private function askAge()
    {
        $user = BotUser::where('chatId', $this->chat_id)->first();
        $user->step = BotUser::STEP_ASK_AGE;
        $option = [
            [$this->telegram->buildKeyboardButton("Iltimos yoshingizni kiriting")],
        ];
        $keyb = $this->telegram->buildKeyBoard($option, $onetime=true, $resize=true);
        $content = array('chat_id' => $this->chat_id, 'reply_markup' => $keyb, 'text' => "Jinsingizni belgilang");
        $this->telegram->sendMessage($content);
    }

    private function askGender()
    {
        $option = [
            [$this->telegram->buildKeyboardButton("Kontraktni ulashish", $request_contact = true)],
        ];
        $keyb = $this->telegram->buildKeyBoard($option, $onetime=true, $resize=true);
        $content = array('chat_id' => $this->chat_id, 'reply_markup' => $keyb, 'text' => "Iltimos kontaktingizni jo'nating");
        $this->telegram->sendMessage($content);
    }

    private function askSpeciality()
    {
        $option = [
            [$this->telegram->buildKeyboardButton("Kontraktni ulashish", $request_contact = true)],
        ];
        $keyb = $this->telegram->buildKeyBoard($option, $onetime=true, $resize=true);
        $content = array('chat_id' => $this->chat_id, 'reply_markup' => $keyb, 'text' => "Iltimos kontaktingizni jo'nating");
        $this->telegram->sendMessage($content);
    }

    private function askExperience()
    {
        $option = [
            [$this->telegram->buildKeyboardButton("Kontraktni ulashish", $request_contact = true)],
        ];
        $keyb = $this->telegram->buildKeyBoard($option, $onetime=true, $resize=true);
        $content = array('chat_id' => $this->chat_id, 'reply_markup' => $keyb, 'text' => "Iltimos kontaktingizni jo'nating");
        $this->telegram->sendMessage($content);
    }

    private function showStart()
    {
        $user = BotUser::where('chatId', $this->chat_id)->first();
        $user->step = BotUser::STEP_ASK_FULLNAME;
        $user->save();
        $option = [
            [$this->telegram->buildKeyboardButton("E'lon joylashtirish")]
        ];
        $keyb = $this->telegram->buildKeyBoard($option, $onetime=false, $resize=true);
        $content = array('chat_id' => $this->chat_id, 'reply_markup' => $keyb, 'text' => "O'zingizga munosib ish topish ish uchun faoliyatingiz haqida ma'lumot bering va so'ralgan ma'lumotlarni qoldiring");
        $this->telegram->sendMessage($content);
    }

}