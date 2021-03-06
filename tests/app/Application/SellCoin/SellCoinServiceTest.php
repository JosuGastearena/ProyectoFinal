<?php

namespace Tests\app\Application\SellCoin;

use App\Application\Coin\SellCoinService;
use App\Application\CryptoCurrenciesDataSource\CryptoCurrenciesDataSource;
use App\Domain\Coin;
use App\Domain\Wallet;
use Mockery;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Tests\TestCase;

class SellCoinServiceTest extends TestCase
{
    private SellCoinService $sellCoinService;
    private CryptoCurrenciesDataSource $cryptoCurrenciesDataSource;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->cryptoCurrenciesDataSource = Mockery::mock(CryptoCurrenciesDataSource::class);
        $this->sellCoinService = new SellCoinService($this->cryptoCurrenciesDataSource);
    }

    /**
     * @test
     */
    public function soldGivenCoinAmount()
    {
        $coin_id = "1";
        $wallet_id = "2";
        $amount_usd = 100;

        $wallet = new Wallet($wallet_id, []);
        $coin = new Coin($coin_id, "*", "Crypt", "1", 1, "100");
        $wallet->addCoin($coin, $amount_usd / $coin->getPrice_usd());

        $wallet2 = new Wallet($wallet_id, []);

        $this->cryptoCurrenciesDataSource
            ->expects('sellCoin')
            ->with($coin_id, $amount_usd)
            ->once()
            ->andReturn(1);

        $this->cryptoCurrenciesDataSource
            ->expects('getsWalletCryptocurrencies')
            ->with($wallet_id)
            ->once()
            ->andReturn($wallet);

        $this->cryptoCurrenciesDataSource
            ->expects('coinStatus')
            ->with($coin_id)
            ->once()
            ->andReturn($coin);

        $this->cryptoCurrenciesDataSource
            ->expects('addWallet')
            ->with($wallet)
            ->once()
            ->andReturn();

        $this->sellCoinService->execute($coin_id, $wallet_id, $amount_usd);

        $this->assertEquals($wallet, $wallet2);
    }

    /**
     * @test
     */
    public function serviceUnavailableWhenIDIntroduced()
    {
        $coin_id = "1";
        $wallet_id = "2";
        $amount_usd = 100;

        $this->cryptoCurrenciesDataSource
            ->expects('sellCoin')
            ->with('1', 100)
            ->once()
            ->andThrows(new ServiceUnavailableHttpException(0, 'Service unavailable'));

        $this->expectException(ServiceUnavailableHttpException::class);

        $this->sellCoinService->execute($coin_id, $wallet_id, $amount_usd);
    }

    /**
     * @test
     */
    public function coinNotFoundWhenIDIntroduced()
    {
        $coin_id = "1";
        $wallet_id = "2";
        $amount_usd = 100;

        $this->cryptoCurrenciesDataSource
            ->expects('sellCoin')
            ->with('1', 100)
            ->once()
            ->andThrows(new NotFoundHttpException('Coin not found'));


        $this->expectException(NotFoundHttpException::class);

        $this->sellCoinService->execute($coin_id, $wallet_id, $amount_usd);
    }
}
