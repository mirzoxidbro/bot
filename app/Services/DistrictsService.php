<?php

namespace App\Services;

use App\Models\District;
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

        $district = District::get();
        // info($district);
        return $district;
        // if($district)
        // {
        //     return $district->$lang;
        // }
        // return '';
    }
}