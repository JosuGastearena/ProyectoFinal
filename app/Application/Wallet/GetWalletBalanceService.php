<?php

namespace App\Application\Wallet;

use App\Application\CryptoCurrenciesDataSource\CryptoCurrenciesDataSource;
use App\Domain\Coin;
use App\Domain\Wallet;
use Exception;
use PHPUnit\Util\Json;

class GetWalletBalanceService
{
    private CryptoCurrenciesDataSource $cryptoCurrenciesDataSource;

    public function __construct(CryptoCurrenciesDataSource $cryptoCurrenciesDataSource)
    {
        $this->cryptoCurrenciesDataSource = $cryptoCurrenciesDataSource;
    }

    public function execute(string $walletID): float
    {
        return $this->cryptoCurrenciesDataSource->getsWalletBalance($walletID);
    }
}
