<?php

namespace App\Application\Wallet;

use App\Application\CryptoCurrenciesDataSource\CryptoCurrenciesDataSource;

class GetWalletBalanceService
{
    private CryptoCurrenciesDataSource $cryptoCurrenciesDataSource;

    public function __construct(CryptoCurrenciesDataSource $cryptoCurrenciesDataSource)
    {
        $this->cryptoCurrenciesDataSource = $cryptoCurrenciesDataSource;
    }

    public function execute(string $wallet_id): float
    {
        return $this->cryptoCurrenciesDataSource->getsWalletBalance($wallet_id);
    }
}
