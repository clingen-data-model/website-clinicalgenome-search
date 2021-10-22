<?php
declare(strict_types=1);

namespace App\DataExchange\Contracts;

interface MessageHandler
{
    public function setNext(MessageHandler $handler): MessageHandler;

    public function handle(\RdKafka\Message $message);
}