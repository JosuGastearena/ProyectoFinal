<?php

namespace App\Application\GetCoin;


use App\Application\CryptoCurrenciesDataSource\CryptoCurrenciesDataSource;
use App\Domain\Coin;
use Exception;
use PHPUnit\Util\Json;

class GetCoinService
{
    /**
     * @var CryptoCurrenciesDataSource
     */
    private CryptoCurrenciesDataSource $cryptoCurrenciesDataSource;

    /**
     * IsEarlyAdopterService constructor.
     * @param CryptoCurrenciesDataSource $cryptoCurrenciesDataSource
     */
    public function __construct(CryptoCurrenciesDataSource $cryptoCurrenciesDataSource)
    {
        $this->cryptoCurrenciesDataSource = $cryptoCurrenciesDataSource;
    }

    /**
     * @param string $coinID
     * @return Coin
     */
    public function execute(string $coinID): Coin
    {
        return $this->cryptoCurrenciesDataSource->coinStatus($coinID);
    }
}
