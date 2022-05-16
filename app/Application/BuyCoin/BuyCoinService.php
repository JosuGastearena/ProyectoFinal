<?php

namespace App\Application\BuyCoin;


use App\Application\CryptoCurrenciesDataSource\CryptoCurrenciesDataSource;
use App\Domain\Coin;
use Exception;
use PHPUnit\Util\Json;

class BuyCoinService
{
    private CryptoCurrenciesDataSource $cryptoCurrenciesDataSource;

    public function __construct(CryptoCurrenciesDataSource $cryptoCurrenciesDataSource)
    {
        $this->cryptoCurrenciesDataSource = $cryptoCurrenciesDataSource;
    }

    public function execute(string $coin_id, float $amount_usd): float
    {
        return $this->cryptoCurrenciesDataSource->buyCoin($coin_id, $amount_usd);
    }
}
