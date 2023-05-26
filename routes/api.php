<?php

use App\Http\Controllers\BotController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use GuzzleHttp\Client;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('test', function(Request $request){
    Log::alert($request);
});


Route::post('/webhook', function () {
    $update = json_decode(file_get_contents('php://input'), true);
    $chat_id = $update['message']['chat']['id'];
    $text = 'Assalomu alaykum';

    $client = new Client();
    $response = $client->post('https://api.telegram.org/bot'.env('TELEGRAM_BOT_TOKEN').'/sendMessage', [
        'form_params' => [
            'chat_id' => $chat_id,
            'text' => $text
        ]
    ]);

    return 'ok';
});
