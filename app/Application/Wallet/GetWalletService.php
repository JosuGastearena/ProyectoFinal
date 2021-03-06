<?php

namespace App\Application\Wallet;

use App\Application\CryptoCurrenciesDataSource\CryptoCurrenciesDataSource;
use App\Domain\Wallet;

class GetWalletService
{
    private CryptoCurrenciesDataSource $cryptoCurrenciesDataSource;

    public function __construct(CryptoCurrenciesDataSource $cryptoCurrenciesDataSource)
    {
        $this->cryptoCurrenciesDataSource = $cryptoCurrenciesDataSource;
    }

    public function execute(string $wallet_id): Wallet
    {
        return $this->cryptoCurrenciesDataSource->getsWalletCryptocurrencies($wallet_id);
    }
}
