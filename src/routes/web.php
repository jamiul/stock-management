<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RabbitMQController;

Route::get('/', function () {
    return view('welcome');
});

// Send a message to RabbitMQ
// Route::get('/send', [RabbitMQController::class, 'send']);
// Route::get('/consumer/create', [RabbitMQController::class, 'consumerCreatePDF']);
// Route::get('/consumer/log', [RabbitMQController::class, 'consumerLogPDF']);

// Test rabbitmq connection
Route::get('/connect-rabbitmq', function () {
    $rabbitMQService = app()->make(\App\Services\RabbitMQService::class);
    $rabbitMQService->publishMessage('say_hi', [
        'message' => 'Hello from Laravel'
    ]);

    return 'Connected to RabbitMQ!';
});

// Test sending email
Route::get('/send-email', function () {
    $product = App\Models\Product::find(1);
    \App\Jobs\SendLowStockNotification::dispatch($product);

    return 'Email sent!';
});
