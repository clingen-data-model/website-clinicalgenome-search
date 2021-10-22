<?php

namespace App\DataExchange\MessagePushers;

use App\DataExchange\Contracts\MessagePusher;
use App\DataExchange\Exceptions\StreamingServiceException;
use App\DataExchange\Exceptions\StreamingServiceDisabledException;

class DisabledPusher implements MessagePusher
{
    public function topic(string $topic)
    {
        $this->topic = $topic;
        return $this;
    }

    public function push(string $message, $uuid = null)
    {
        $shortMessage = strlen($message) > 50 ? substr($message, 0, 50)."..." : $message;
        throw new StreamingServiceDisabledException('Pushing to the streaming service is disabled.  The message "'.$shortMessage.'"for topic "'.$this->topic.'" has been stored but has not been sent.');
    }
}
