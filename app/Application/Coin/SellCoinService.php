<?php

namespace App\Application\Coin;

use App\Application\CryptoCurrenciesDataSource\CryptoCurrenciesDataSource;

class SellCoinService
{
    private CryptoCurrenciesDataSource $cryptoCurrenciesDataSource;

    public function __construct(CryptoCurrenciesDataSource $cryptoCurrenciesDataSource)
    {
        $this->cryptoCurrenciesDataSource = $cryptoCurrenciesDataSource;
    }

    public function execute(string $coin_id, string $wallet_id, float $amount_usd): void
    {
        $bought_amount = $this->cryptoCurrenciesDataSource->sellCoin($coin_id, $amount_usd);
        $wallet = $this->cryptoCurrenciesDataSource->getsWalletCryptocurrencies($wallet_id);
        $coin = $this->cryptoCurrenciesDataSource->coinStatus($coin_id);
        $wallet->sellCoin($coin, $bought_amount);
        $this->cryptoCurrenciesDataSource->addWallet($wallet);
    }
}
