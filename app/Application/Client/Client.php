<?php

namespace App\Application\Client;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class Client
{
    function getCoinStatus(string $coin_id): Response{
        return Http::get('https://api.coinlore.net/api/ticker/?id='.$coin_id);
    }
}
