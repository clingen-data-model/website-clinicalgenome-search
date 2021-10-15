<?php

namespace App\DataExchange\Contracts;

use App\Curation;
use App\Gci\GciMessage;
use App\DataExchange\Maps\GciStatusMap;
use App\Gci\GciClassificationMap;

interface GeneValidityCurationUpdateJob
{
    public function __construct(GciStatusMap $statusMap, GciClassificationMap $classificationMap, Curation $curation, GciMessage $message);
    public function handle();
}
