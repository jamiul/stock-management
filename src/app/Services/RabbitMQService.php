<?php
namespace App\Services;

use App\Models\Product;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMQService
{
    protected $connection;
    protected $channel;

    public function __construct()
    {
        // Increase the timeout duration to 10 seconds (or a value that works for your case)
        $this->connection = new AMQPStreamConnection(
            'stock-rabbitmq',       // RabbitMQ hostname
            5672,             // RabbitMQ default port
            'stockuser',          // Username
            'stock123',          // Password
            '/',              // Virtual host (default)
            false,            // Use TLS
            'AMQPLAIN',       // Authentication method
            null,             // No additional parameters
            'en_US',
            30,                //Connection Timeout
            30                 // Read/Write Timeout
        );
        $this->channel = $this->connection->channel();
    }

    public function publishMessage(string $queueName, array $data): void
    {
        $this->channel->queue_declare($queueName, false, true, false, false);

        $msg = new AMQPMessage(json_encode($data), [
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
        ]);

        $this->channel->basic_publish($msg, '', $queueName);
    }

    public function consumeMessage(string $queueName, callable $callback): void
    {
        $this->channel->queue_declare($queueName, false, true, false, false);

        $this->channel->basic_consume(
            $queueName,
            '',
            false,
            true,
            false,
            false,
            $callback
        );

        while ($this->channel->is_consuming()) {
            $this->channel->wait();
        }
    }

    public function close(): void
    {
        $this->channel->close();
        $this->connection->close();
    }

    public function checkStock($productId)
    {
        $product = Product::find($productId);

        if ($product->stock < $product->min_stock) {
            // Publish a message to RabbitMQ
            $rabbitMQ = new RabbitMQService();
            $rabbitMQ->publishMessage('low_stock_queue', [
                'id' => $product->id,
                'name' => $product->name,
                'stock' => $product->stock,
            ]);
        }

        return response()->json(['message' => 'Stock checked and notification sent if needed']);
    }
}
