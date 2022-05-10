<?php

namespace App\Domain;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CryptoCurrenciesCache
{
    public function __construct()
    {
        /*
        Schema::create('cache', function ($table) {
            $table->string('id_wallet')->unique();
            $table->array('list_coin');
        });
        */
    }

    public function get($id_wallet): Wallet
    {
        if (!Cache::has($id_wallet)) {
            throw new NotFoundHttpException('Wallet not found');
        } else {
            return new Wallet($id_wallet, Cache::get($id_wallet));
        }
    }

    public function set($wallet): void
    {
        Cache::put($wallet->getWalletId(), $wallet->getListCoin());
    }

    public function openWallet(): Wallet
    {
        $id_wallet = (string) rand(1, 1000);
        while (Cache::get($id_wallet) != null) {
            $id_wallet = (string) rand(1, 1000);
        }
        $wallet = new Wallet($id_wallet, []);
        $this->set($wallet);
        return $wallet;
    }
}
