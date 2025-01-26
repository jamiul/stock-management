<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RabbitMQController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/send', [RabbitMQController::class, 'send']);
Route::get('/consumer/create', [RabbitMQController::class, 'consumerCreatePDF']);
Route::get('/consumer/log', [RabbitMQController::class, 'consumerLogPDF']);

Route::get('/send-message', function () {
    $rabbitMQService = app()->make(\App\Services\RabbitMQService::class);
    $rabbitMQService->publishMessage('say_hi', [
        'message' => 'Hello from Laravel'
    ]);

    return 'Message sent to RabbitMQ!';
});
