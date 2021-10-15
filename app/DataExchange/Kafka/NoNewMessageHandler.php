<?php
declare(strict_types=1);

namespace App\DataExchange\Kafka;

use App\DataExchange\Exceptions\StreamingServiceEndOfFIleException;

class NoNewMessageHandler extends AbstractMessageHandler
{
    private $noActionErrors = [
        RD_KAFKA_RESP_ERR__PARTITION_EOF,
    ];

    public function handle(\RdKafka\Message $message)
    {
        if ($message->err == RD_KAFKA_RESP_ERR__PARTITION_EOF) {
            throw new StreamingServiceEndOfFIleException('No new messages in partition', $message->err);
        }

        parent::handle($message);
    }
}
