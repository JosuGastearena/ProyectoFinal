<?php

namespace App\Application\CryptoCurrenciesDataSource;

use App\Domain\Coin;
use App\Domain\Wallet;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class CryptoCurrenciesDataSource implements CurrenciesDataSource
{
    public function coinStatus(string $coinID): Coin
    {
        $response = Http::get('https://api.coinlore.net/api/ticker/?id='.$coinID);
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
        $id_wallet = (string) rand(1, 1000);
        while (Cache::get($id_wallet) != null) {
            $id_wallet = (string) rand(1, 1000);
        }
        $wallet = new Wallet($id_wallet);
        Cache::put($wallet->getWalletId(), $wallet->getListCoin());
        return $wallet;
    }
}
