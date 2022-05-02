<?php

namespace App\Application\CryptoCurrenciesDataSource;

use App\Domain\Coin;

interface CurrenciesDataSource
{
    public function coinStatus(string $coinID): Coin;
}
