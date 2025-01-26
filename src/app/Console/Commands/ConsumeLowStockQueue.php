<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\RabbitMQService;
use App\Jobs\SendLowStockNotification;

class ConsumeLowStockQueue extends Command
{
    protected $signature = 'queue:consume-low-stock';
    protected $description = 'Consume the low stock queue and dispatch notifications';

    public function handle()
    {
        $rabbitMQ = new RabbitMQService();

        $rabbitMQ->consumeMessage('low_stock_queue', function ($msg) {
            $data = json_decode($msg->body, true);
            SendLowStockNotification::dispatch($data);
        });

        $rabbitMQ->close();
    }
}
