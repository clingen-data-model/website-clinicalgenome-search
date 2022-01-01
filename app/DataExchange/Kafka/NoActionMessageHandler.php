<?php
declare(strict_types=1);

namespace App\DataExchange\Kafka;

class NoActionMessageHandler extends AbstractMessageHandler
{
    private $noActionErrors = [
        RD_KAFKA_RESP_ERR__TIMED_OUT
    ];

    public function handle(\RdKafka\Message $message)
    {
        if (in_array($message->err, $this->noActionErrors)) {
            return;
        }

        if ($this->isUnpublishedMessage($message)) {
            return;
        }

        return parent::handle($message);
    }

    private function isUnpublishedMessage(\RdKafka\Message $message)
    {
        if (is_null($message->payload)) {
            return false;
        }

        $payload = json_decode($message->payload);

        if (is_null($payload)) {
            return false;
        }

        if (!isset($payload->status)) {
            return false;
        }

        if (is_object($payload->status) && $payload->status->name == 'unpublished') {
            return true;
        }

        if (is_string($payload->status) && $payload->status == 'unpublished') {
            return true;
        }

        return false;
    }
    
}
