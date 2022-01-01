<?php

namespace App\DataExchange\Kafka;

use App\IncomingStreamMessage;
use Illuminate\Support\Facades\Log;

class StoreMessageHandler extends AbstractMessageHandler
{
    public function handle(\RdKafka\Message $message)
    {
        $payload = json_decode($message->payload);
        $storedMessage = IncomingStreamMessage::firstOrCreate([
            'key' => $this->hasUuid($message->payload) ? $payload->report_id.'-'.$payload->date : null,
        ], [
            'timestamp' => $message->timestamp,
            'topic' => $message->topic_name,
            'partition' => $message->partition,
            'offset' => $message->offset,
            'error_code' => $message->err,
            'payload' => $payload,
            'gdm_uuid' => $this->hasUuid($message->payload) ? $payload->report_id : null
        ]);

        if ($storedMessage->payload != $payload) {
            Log::warning('We got a message from the gene_validity_events with a key that already exists and a payload that is different', ['storedMessage->payload' => $storedMessage->payload, 'payload' => $payload]);
            die;
        }

        return parent::handle($message);
    }

    private function hasUuid($payload)
    {
        $data = json_decode($payload);
        if ($data && is_object($data) && isset($data->report_id)) {
            return true;
        }
        return false;
    }
}
