<?php

namespace App\Services;

use App\Models\BotUser;
use DateTime;

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
        $text = $this->telegram->Text();
        // $message = $data['message'];
        // $text = $message['text'];
        // $chat_id = $message['chat']['id'];
        $chat_id = $this->telegram->ChatID();
        if($text == '/start'){
            $this->pagination->setLanguage($chat_id, 'ru');
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
                    if($text == "💼 ". $this->texts->getText('vacancies', @$chat_id)){
                        $this->showVacancyStepOne($chat_id);
                    }

                    break;

                case 'vacancy_step_one':
                    if($text == $this->texts->getText('back', @$chat_id)." ↩" || $text == "🏠 ". $this->texts->getText('main_menu', @$chat_id))
                    {
                        $this->showMain($chat_id);
                    }elseif($text == "✅ ". $this->texts->getText('agree', @$chat_id))
                    {
                        $this->askFullName($chat_id);
                    }
                    break;

                case 'ask_full_name':
                        if($text == $this->texts->getText('back', @$chat_id)." ↩"){
                            $this->showVacancyStepOne($chat_id);
                        }elseif($text == "🏠 ". $this->texts->getText('main_menu', @$chat_id)){
                            $this->showMain(@$chat_id);
                        }else{
                            $this->writeUserFullName(@$chat_id, $text);
                        }
                    break;
                
                case 'ask_age':
                    if($text == $this->texts->getText('back', @$chat_id)." ↩"){
                        $this->askFullName($chat_id);
                    }elseif($text == "🏠 ". $this->texts->getText('main_menu', @$chat_id)){
                        $this->showMain(@$chat_id);
                    }else{
                        $this->writeAge($chat_id, $text);
                    }
                    break;

                case 'ask_technologies':
                    if($text == $this->texts->getText('back', @$chat_id)." ↩"){
                        $this->askAge($chat_id);
                    }elseif($text == "🏠 ". $this->texts->getText('main_menu', @$chat_id)){
                        $this->showMain(@$chat_id);
                    }else{
                        $this->writeKnowledges($chat_id, $text);
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

    private function showVacancyStepOne($chat_id)
    {
        $this->pagination->setPage($chat_id, 'vacancy_step_one');

        $content = array('chat_id' => $chat_id, 'disable_web_page_preview' => false,'text' => "Присоединяйтесь в нашу команду!!!😊");
        $this->telegram->sendMessage($content);

        $option = [
            [$this->telegram->buildKeyboardButton("✅ ". $this->texts->getText('agree', @$chat_id))],
            [$this->telegram->buildKeyboardButton("🏠 ". $this->texts->getText('main_menu', @$chat_id)), $this->telegram->buildKeyboardButton($this->texts->getText('back', @$chat_id)." ↩")]
        ];
        $keyb = $this->telegram->buildKeyBoard($option, $onetime=false, $resize=true);
        $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "Даете ли Вы согласие на обработку ваших персональных данных?");
        $this->telegram->sendMessage($content);
    }

    private function askFullName($chat_id)
    {
        $this->pagination->setPage($chat_id, 'ask_full_name');
        $user = BotUser::where('chatId', $chat_id)->first();
        $option = [
            // [$this->telegram->buildKeyboardButton($user->fullname)],
            [$this->telegram->buildKeyboardButton("🏠 ". $this->texts->getText('main_menu', @$chat_id)), $this->telegram->buildKeyboardButton($this->texts->getText('back', @$chat_id)." ↩")]
        ];
        $keyb = $this->telegram->buildKeyBoard($option, $onetime=false, $resize=true);
        $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "Iltimos ismingizni kiriting!");
        $this->telegram->sendMessage($content);
    }

    private function writeUserFullName($chat_id, $text)
    {
        $this->askAge($chat_id);

        $user = BotUser::where('chatId', $chat_id)->first();
        $user->fullname = $text; $user->save();
    }

    private function askAge($chat_id)
    {
        $this->pagination->setPage($chat_id, 'ask_age');

        $content = array('chat_id' => $chat_id, 'disable_web_page_preview' => false,'text' => "Iltimos tug'ilgan sanangizni kiriting!");
        $this->telegram->sendMessage($content);    

        $option = [
            [$this->telegram->buildKeyboardButton("🏠 ". $this->texts->getText('main_menu', @$chat_id)), $this->telegram->buildKeyboardButton($this->texts->getText('back', @$chat_id)." ↩")]
        ];
        $keyb = $this->telegram->buildKeyBoard($option, $onetime=false, $resize=true);
        $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "2001-03-22 sana shu formatda bo'lishi kerak!");
        $this->telegram->sendMessage($content);
    }

    private function writeAge($chat_id, $text){
        if($this->validateDate($text)){
            $this->pagination->setPage($chat_id, 'ask_technologies');
            $user = BotUser::where('chatId', $chat_id)->first();
            $user->birth_date = $text; 
            $user->save();
            $option = [
                [$this->telegram->buildKeyboardButton("🏠 ". $this->texts->getText('main_menu', @$chat_id)), $this->telegram->buildKeyboardButton($this->texts->getText('back', @$chat_id)." ↩")]
            ];
            $keyb = $this->telegram->buildKeyBoard($option, $onetime=false, $resize=true);
            $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "Biladigan texnologiyalaringiz haqida ma'lumot bering");
            $this->telegram->sendMessage($content);
        }else{
            $content = array('chat_id' => $chat_id, 'disable_web_page_preview' => false,'text' => "Iltimos yoshni to'g'ri formatda kiriting!");
            $this->telegram->sendMessage($content);    
        }

    }

    function validateDate($date, $format = 'Y-m-d'){
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    private function writeKnowledges($chat_id, $text)
    {
        $this->pagination->setPage($chat_id, 'ask_age');
        $user = BotUser::where('chatId', $chat_id)->first();
        $user->knowledge = $text; $user->save();
        $content = array('chat_id' => $chat_id, 'disable_web_page_preview' => false,'text' => "Ma'lumotlaringiz qabul qilindi. 24 soat ichida o'z e'loningizni ish.uz job portalida ko'rishingiz mumkin!");
        $this->telegram->sendMessage($content);  
    }

}