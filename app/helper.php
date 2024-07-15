<?php

use App\Mail\OrderEmail;
use Illuminate\Support\Facades\Mail;
use App\Models\Order;

function invoiceEmail($orderId)
{
    $order = Order::where('id', $orderId)->with('orderItems')->first();

    $mailData = [
        'subject' => 'Thanks for your order',
        'order' => $order
    ];

    Mail::to($order->email)->send(new OrderEmail($mailData));
}
