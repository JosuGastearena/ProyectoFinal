<?php

namespace App\Application\GetCoin;

use App\Application\CryptoCurrenciesDataSource\CryptoCurrenciesDataSource;
use App\Domain\Coin;
use Exception;
use Illuminate\Http\JsonResponse;
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
     * @return Json
     * @throws Exception
     */
    public function execute(string $coinID): Coin
    {
        $coin = $this->cryptoCurrenciesDataSource->coinStatus($coinID);

        return $coin;
    }
}
