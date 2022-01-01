<?php

namespace App\DataExchange\Kafka;

use App\DataExchange\Exceptions\KafkaEnvironmentException;

class KafkaEnvValidator
{
    public function __invoke()
    {
        $ssConfig = config('dx');
        foreach (['username', 'password', 'group'] as $config) {
            if (!isset($ssConfig[$config]) || !$ssConfig[$config]) {
                throw new KafkaEnvironmentException('Missing kafka environment variable '.strtoupper($config));
            }
        }
    }
}
