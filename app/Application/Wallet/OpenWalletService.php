<?php

namespace App\Application\Wallet;

use App\Application\CryptoCurrenciesDataSource\CryptoCurrenciesDataSource;
use App\Domain\Wallet;
use Exception;

class OpenWalletService
{
    /**
     * @var CryptoCurrenciesDataSource
     */
    private CryptoCurrenciesDataSource $cryptoCurrenciesDataSource;

    /**
     * OpenWalletService constructor.
     * @param CryptoCurrenciesDataSource $cryptoCurrenciesDataSource
     */
    public function __construct(CryptoCurrenciesDataSource $cryptoCurrenciesDataSource)
    {
        $this->cryptoCurrenciesDataSource = $cryptoCurrenciesDataSource;
    }

    /**
     * @return Wallet
     * @throws Exception
     */
    public function execute(): Wallet
    {
        return $this->cryptoCurrenciesDataSource->openWallet();
    }
}
