<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMQController extends Controller
{
    protected $connection;
    protected $channel;


    public function __construct()
    {
        $this->connection = new AMQPStreamConnection('rabbitmq', 5672, 'stockuser', 'stock123', '/');
        $this->channel = $this->connection->channel();

        $this->channel->exchange_declare('pdf_events', 'direct', false, true, false);

        $this->channel->queue_declare('create_pdf_queue', false, true, false, false, false);
        $this->channel->queue_declare('pdf_log_queue', false, true, false, false, false);

        $this->channel->queue_bind('create_pdf_queue', 'pdf_events', 'pdf_create', false);
        $this->channel->queue_bind('pdf_log_queue', 'pdf_events', 'pdf_log', false);
    }

    protected function close()
    {
        $this->channel->close();
        $this->connection->close();
    }

    public function send()
    {
        $message = new AMQPMessage('I hope this message will be sent to the queue');

        $this->channel->basic_publish($message, 'pdf_events', 'pdf_create');
        $this->channel->basic_publish($message, 'pdf_events', 'pdf_log');

        $this->close();

        echo " [x] Message sent: I hope this message will be sent to the queue \n";
    }

    public function consumerCreatePDF()
    {
        $callback = function ($msg) {
            Log::info('[x] Received: ', [$msg->getBody()]);
        };

        $this->channel->basic_consume('create_pdf_queue', 'create_pdf_queue', false, true, false, false, $callback);

        try {
            /** Usar apenas se precisar deixar o canal aberto */
            // $this->channel->consume();
        } catch (\Throwable $exception) {
            Log::error($exception->getMessage());
        }

        $this->close();
    }

    public function consumerLogPDF()
    {
        $callback = function ($msg) {
            echo " [x] Received: ", $msg->body, "\n";
            Log::info('[x] Received: ', [$msg->getBody()]);
        };

        $this->channel->basic_consume('pdf_log_queue', 'pdf_log_queue', false, true, false, false, $callback);

        try {
            /** Usar apenas se precisar deixar o canal aberto */
            // $this->channel->consume();
        } catch (\Throwable $exception) {
            Log::error($exception->getMessage());
        }

        $this->close();
    }
}
