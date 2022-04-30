<?php

namespace App\Domain;

class Coin
{
    private string $coin_id;
    private string $symbol;
    private string $name;
    private string $name_id;
    private int $rank;
    private string $price_usd;

    public function __construct(string $coin_id, string $symbol, string $name, string $name_id, int $rank, string $price_usd)
    {
        $this->coin_id = $coin_id;
        $this->symbol = $symbol;
        $this->name = $name;
        $this->name_id = $name_id;
        $this->rank = $rank;
        $this->price_usd = $price_usd;
    }

    public function getCoin_id(): string
    {
        return $this->coin_id;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getName_id(): string
    {
        return $this->name_id;
    }

    public function getRank(): int
    {
        return $this->rank;
    }

    public function getPrice_usd(): string
    {
        return $this->price_usd;
    }
}
