<?php

namespace App\Application\CryptoCurrenciesDataSource;

use App\Domain\Coin;
use App\Domain\CryptoCurrenciesCache;
use App\Domain\Wallet;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class CryptoCurrenciesDataSource implements CurrenciesDataSource
{
    private CryptoCurrenciesCache $cache;

    public function __construct()
    {
        $this->cache = new CryptoCurrenciesCache();
    }

    public function coinStatus(string $coin_id): Coin
    {
        $response = Http::get('https://api.coinlore.net/api/ticker/?id='.$coin_id);
        try{
            $data = json_decode($response, true);
            $coin = new Coin($data[0]['id'], $data[0]['symbol'], $data[0]['name'], $data[0]['nameid'], $data[0]['rank'], $data[0]['price_usd']);

        }catch (\Exception $ex){
            throw new NotFoundHttpException();
        }
        return $coin;
    }

    public function openWallet(): Wallet
    {
        return $this->cache->openWallet();
    }

    public function getsWalletCryptocurrencies(string $id_wallet): Wallet
    {
        return $this->cache->get($id_wallet);
    }

    public function buyCoin(string $coin_id, float $amount_usd): float
    {
        $coin = $this->coinStatus($coin_id);
        return $amount_usd / floatval($coin->getPrice_usd());
    }
}
