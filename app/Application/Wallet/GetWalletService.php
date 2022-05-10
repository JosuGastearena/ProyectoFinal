<?php

namespace App\Application\Wallet;


use App\Application\CryptoCurrenciesDataSource\CryptoCurrenciesDataSource;
use App\Domain\Coin;
use App\Domain\Wallet;
use Exception;
use PHPUnit\Util\Json;

class GetWalletService
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
     * @param string $walletID
     * @return Wallet
     */
    public function execute(string $walletID): Wallet
    {
        return $this->cryptoCurrenciesDataSource->getsWalletCryptocurrencies($walletID);
    }
}
