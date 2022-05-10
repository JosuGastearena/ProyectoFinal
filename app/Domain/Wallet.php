<?php

namespace App\Domain;

class Wallet
{

    private string $wallet_id;
    private array $list_coin;

    public function __construct(string $wallet_id, array $list_coin)
    {

        $this->wallet_id = $wallet_id;
        $this->list_coin =$list_coin;
    }

    public function getWalletId(): string
    {
        return $this->wallet_id;
    }

    public function getListCoin(): array
    {
        return $this->list_coin;
    }

    public function addCoin(Coin $coin, float $amount): void
    {
        $indexCoin = -1;
        for($i=0;$i<count($this->list_coin);$i++){
            if($this->list_coin[$i][0]->getCoin_id() == $coin->getCoin_id()){
                $this->list_coin[$i][1] += $amount;
                $indexCoin = $i;
                break;
            }
        }
        if($indexCoin == -1){
            $this->list_coin[] = [$coin, $amount];
        }
    }
}
