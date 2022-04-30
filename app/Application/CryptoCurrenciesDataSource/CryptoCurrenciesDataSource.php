<?php

namespace App\Application\CryptoCurrenciesDataSource;

use App\Domain\Coin;

interface CryptoCurrenciesDataSource
{
    public function coinStatus(string $coinID): Coin;
}
