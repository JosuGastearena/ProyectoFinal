<?php

namespace App\Application\CryptoCurrenciesDataSource;

use App\Domain\Coin;
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
}
