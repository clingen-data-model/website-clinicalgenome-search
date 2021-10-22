<?php

namespace App\DataExchange\Contracts;

interface MessagePusher
{
    public function topic(string $topic);
    public function push(string $message, $uuid = null);
}
