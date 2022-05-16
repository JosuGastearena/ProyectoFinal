<?php

namespace App\Application\CryptoCurrenciesDataSource;

use App\Domain\Coin;
use App\Domain\Wallet;

interface CurrenciesDataSource
{
    public function coinStatus(string $coin_id): Coin;
    public function openWallet(): Wallet;
    public function getsWalletCryptocurrencies(string $id_wallet): Wallet;
    public function buyCoin(string $coin_id, float $amount_usd): float;
}
