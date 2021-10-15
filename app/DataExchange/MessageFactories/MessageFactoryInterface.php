<?php

namespace App\DataExchange\MessageFactories;

use App\Curation;

interface MessageFactoryInterface
{
    public function make(Curation $curation, $eventType): array;
}
