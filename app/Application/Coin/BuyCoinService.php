<?php

namespace App\Application\Coin;

use App\Application\CryptoCurrenciesDataSource\CryptoCurrenciesDataSource;

class BuyCoinService
{
    private CryptoCurrenciesDataSource $cryptoCurrenciesDataSource;

    public function __construct(CryptoCurrenciesDataSource $cryptoCurrenciesDataSource)
    {
        $this->cryptoCurrenciesDataSource = $cryptoCurrenciesDataSource;
    }

    public function execute(string $coin_id, string $wallet_id, float $amount_usd): void
    {
        $bought_amount = $this->cryptoCurrenciesDataSource->buyCoin($coin_id, $amount_usd);
        $wallet = $this->cryptoCurrenciesDataSource->getsWalletCryptocurrencies($wallet_id);
        $coin = $this->cryptoCurrenciesDataSource->coinStatus($coin_id);
        $wallet->addCoin($coin, $bought_amount);
        $this->cryptoCurrenciesDataSource->addWallet($wallet);
    }
}
