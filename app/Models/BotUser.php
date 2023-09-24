<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BotUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'chatId',
        'step',
        'gender',
        'age',
        'specialty',
        'experience',
        'fullname',
        'phone_number'
    ];

    const STEP_ASK_FULLNAME = 1;
    const STEP_ASK_AGE = 2;
    const STEP_ASK_GENDER = 3;
    const STEP_ASK_SPECIALITY = 4;
    const STEP_ASK_EXPERIENCE = 5;
    const STEP_ASK_PHONE_NUMBER = 6;

}
