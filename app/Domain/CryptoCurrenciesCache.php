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
            $table->integer('expiration');
        });
        */
    }

    public function get($wallet_id): Wallet
    {
        if (!Cache::has($wallet_id)) {
            throw new NotFoundHttpException('Wallet not found');
        } else {
            return new Wallet($wallet_id, Cache::get($wallet_id));
        }
    }

    public function set($wallet): void
    {
        Cache::put($wallet->getWalletId(), $wallet->getListCoin());
    }

    public function openWallet(): Wallet
    {
        $wallet_id = (string) rand(1, 1000);
        while (Cache::get($wallet_id) != null) {
            $wallet_id = (string) rand(1, 1000);
        }
        $wallet = new Wallet($wallet_id, []);
        $this->set($wallet);
        return $wallet;
    }
}
