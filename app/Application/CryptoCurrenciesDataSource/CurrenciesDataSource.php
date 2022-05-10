<?php

namespace App\Application\CryptoCurrenciesDataSource;

use App\Domain\Coin;
use App\Domain\Wallet;

interface CurrenciesDataSource
{
    public function coinStatus(string $coinID): Coin;
    public function openWallet(): Wallet;
    public function getsWalletCryptocurrencies(string $walletID): Wallet;

}
