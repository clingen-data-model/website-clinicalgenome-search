<?php

namespace App\DataExchange\MessagePushers;

use Ramsey\Uuid\Uuid;
use App\DataExchange\Contracts\MessagePusher;

class MessageLogger implements MessagePusher
{
    public function topic(string $topic)
    {
        $this->topic = $topic;
        return $this;
    }

    public function push(string $message, $uuid = null)
    {
        $uuid = $uuid ?? Uuid::uuid4()->toString();
        \Log::info('Message Pushed', ['topic' => $this->topic, 'message' => $message, 'key' => $uuid]);
    }
}
