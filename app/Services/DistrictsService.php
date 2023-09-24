<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class DistrictsService
{
    public $service;
    public function __construct()
    {
        $this->service = new Pagination();
    }


    public function getDistrict($chat_id)
    {
        $lang = $this->service->getLanguage($chat_id);

        $district = DB::table('districts')->get();
        if($district)
        {
            return $district->$lang;
        }
        return '';
    }
}