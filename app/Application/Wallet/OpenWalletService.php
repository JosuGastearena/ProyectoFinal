<?php

namespace App\Application\Wallet;

use App\Application\CryptoCurrenciesDataSource\CryptoCurrenciesDataSource;
use App\Domain\Wallet;

class OpenWalletService
{
    private CryptoCurrenciesDataSource $cryptoCurrenciesDataSource;

    public function __construct(CryptoCurrenciesDataSource $cryptoCurrenciesDataSource)
    {
        $this->cryptoCurrenciesDataSource = $cryptoCurrenciesDataSource;
    }

    public function execute(): Wallet
    {
        return $this->cryptoCurrenciesDataSource->openWallet();
    }
}
