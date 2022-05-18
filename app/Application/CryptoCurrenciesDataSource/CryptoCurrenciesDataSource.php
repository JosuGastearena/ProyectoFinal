<?php

namespace App\Application\CryptoCurrenciesDataSource;

use App\Application\Client\Client;
use App\Domain\Coin;
use App\Domain\CryptoCurrenciesCache;
use App\Domain\Wallet;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Exception;

class CryptoCurrenciesDataSource implements CurrenciesDataSource
{
    private CryptoCurrenciesCache $cache;
    private Client $client;


    public function __construct()
    {
        $this->cache = new CryptoCurrenciesCache();
        $this->client = new Client();
    }

    public function coinStatus(string $coin_id): Coin
    {
        $response = $this->client->getCoinStatus($coin_id);
        try {
            $data = json_decode($response, true);
            $coin = new Coin($data[0]['id'], $data[0]['symbol'], $data[0]['name'], $data[0]['nameid'], $data[0]['rank'], $data[0]['price_usd']);
        } catch (Exception $ex) {
            throw new NotFoundHttpException();
        }
        return $coin;
    }

    public function openWallet(): Wallet
    {
        return $this->cache->openWallet();
    }

    public function getsWalletCryptocurrencies(string $wallet_id): Wallet
    {
        return $this->cache->get($wallet_id);
    }

    public function buyCoin(string $coin_id, float $amount_usd): float
    {
        return $this->transaction($coin_id, $amount_usd);
    }

    public function sellCoin(string $coin_id, float $amount_usd): float
    {
        return $this->transaction($coin_id, $amount_usd);
    }

    private function transaction(string $coin_id, float $amount_usd): float
    {
        $coin = $this->coinStatus($coin_id);
        return $amount_usd / floatval($coin->getPrice_usd());
    }

    public function getsWalletBalance(string $wallet_id): float
    {
        return $this->getsWalletCryptocurrencies($wallet_id)->getBalance();
    }

    public function addWallet($wallet): void
    {
        $this->cache->set($wallet);
    }

    public function setClient($client): void
    {
        $this->client = $client;
    }
}
