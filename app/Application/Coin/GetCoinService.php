<?php

namespace App\Application\Coin;

use App\Application\CryptoCurrenciesDataSource\CryptoCurrenciesDataSource;
use App\Domain\Coin;

class GetCoinService
{
    private CryptoCurrenciesDataSource $cryptoCurrenciesDataSource;

    public function __construct(CryptoCurrenciesDataSource $cryptoCurrenciesDataSource)
    {
        $this->cryptoCurrenciesDataSource = $cryptoCurrenciesDataSource;
    }

    public function execute(string $coin_id): Coin
    {
        return $this->cryptoCurrenciesDataSource->coinStatus($coin_id);
    }
}
