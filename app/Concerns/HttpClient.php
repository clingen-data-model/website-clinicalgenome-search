<?php

namespace App\Concerns;

use Illuminate\Support\Facades\Http;

trait HttpClient
{
    public function HttpRequest()
    {
        return Http::withoutVerifying();
    }

}
