<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BotTexts extends Model
{
    use HasFactory;

    protected $fillable = [
        'keyword',
        'uz',
        'ru'
    ];
}
