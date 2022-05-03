<?php

namespace App\Domain;

class Wallet
{

    private string $wallet_id;
    private array $list_coin;

    public function __construct(string $wallet_id)
    {
        $this->wallet_id = $wallet_id;
        $this->list_coin = [];
    }

    public function getWalletId(): string
    {
        return $this->wallet_id;
    }

    public function getListCoin(): array
    {
        return $this->list_coin;
    }

    public function addCoin(Coin $coin): void
    {
        $this->list_coin[] = $coin;
    }
}
