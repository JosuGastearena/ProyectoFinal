<?php

namespace App\Application\CryptoCurrenciesDataSource;

use App\Domain\Coin;
use App\Domain\Wallet;

interface CurrenciesDataSource
{
    public function coinStatus(string $coinID): Coin;
    public function openWallet(): Wallet;
    public function getsWalletCryptocurrencies(string $id_wallet): Wallet;
    public function buyCoin(string $coin_id, float $amount_usd): float;
    public function sellCoin(string $coin_id, float $amount_usd): float;
    public function getsWalletBalance(string $wallet_id): float;
}
