<?php
declare(strict_types=1);

namespace App\DataExchange\Kafka;

use App\DataExchange\Events\Received;
use Illuminate\Contracts\Events\Dispatcher;

class SuccessfulMessageHandler extends AbstractMessageHandler
{
    protected $eventDispatcher;

    public function __construct(Dispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function handle(\RdKafka\Message $message)
    {
        if ($message->err == RD_KAFKA_RESP_ERR_NO_ERROR) {
            $this->eventDispatcher->dispatch(new Received($message));
            return;
        }

        return parent::handle($message);
    }
    
}
