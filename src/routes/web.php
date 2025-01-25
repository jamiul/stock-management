<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Jobs\ProcessRabbitMQMessage;
Route::get('/send-message', function () {
    ProcessRabbitMQMessage::dispatch();

    return 'Message sent to RabbitMQ!';
});
