<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Mail\LowStockNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendLowStockNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $product;

    public function __construct($product)
    {
        $this->product = $product;
    }

    public function handle()
    {
        Mail::to('user@example.com')->send(new LowStockNotification($this->product));
    }
}
