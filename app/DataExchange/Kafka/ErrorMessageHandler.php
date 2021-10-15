<?php
declare(strict_types=1);

namespace App\DataExchange\Kafka;

use App\DataExchange\Contracts\MessageHandler;
use App\DataExchange\Exceptions\StreamingServiceException;

class ErrorMessageHandler extends AbstractMessageHandler
{
    protected $knownErrors = [
        RD_KAFKA_RESP_ERR__TIMED_OUT,
        RD_KAFKA_RESP_ERR__PARTITION_EOF
    ];

    public function handle(\RdKafka\Message $message)
    {
        if (!in_array($message->err, $this->knownErrors)) {
            $errMsg = ($message->payload) ? $message->payload : 'An unknown error occurred while consuming Kafka messages';
            throw new StreamingServiceException($errMsg, $message->err);
        }

        return parent::handle($message);
    }
}
