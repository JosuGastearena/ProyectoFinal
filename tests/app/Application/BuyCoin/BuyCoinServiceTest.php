<?php

namespace Tests\app\Application\BuyCoin;

use App\Application\BuyCoin\BuyCoinService;
use App\Application\CryptoCurrenciesDataSource\CryptoCurrenciesDataSource;
use App\Application\CryptoCurrenciesDataSource\CurrenciesDataSource;
use App\Application\GetCoin\GetCoinService;
use App\Domain\Coin;
use App\Domain\Wallet;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Tests\TestCase;
use Mockery;

class BuyCoinServiceTest extends TestCase
{
    private BuyCoinService $buyCoinService;
    private CryptoCurrenciesDataSource $cryptoCurrenciesDataSource;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->cryptoCurrenciesDataSource = Mockery::mock(CryptoCurrenciesDataSource::class);
        $this->buyCoinService = new BuyCoinService($this->cryptoCurrenciesDataSource);
    }

    /**
     * @test
     */
    public function boughtAmountGivenWhenCoinIDAndAmountUSDIntroduced()
    {
        $coin_id = "1";
        $wallet_id = "2";
        $amount_usd = 100;

        $wallet = new Wallet($wallet_id, []);
        $coin = new Coin($coin_id, "*", "Crypt", "1", 1, "100");

        $wallet2 = new Wallet($wallet_id, []);
        $wallet2->addCoin($coin, $amount_usd / $coin->getPrice_usd());


        $this->cryptoCurrenciesDataSource
            ->expects('buyCoin')
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

        $this->buyCoinService->execute($coin_id, $wallet_id, $amount_usd);

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
            ->expects('buyCoin')
            ->with('1', 100)
            ->once()
            ->andThrows(new ServiceUnavailableHttpException(0, 'Service unavailable'));

        $this->expectException(ServiceUnavailableHttpException::class);

        $this->buyCoinService->execute($coin_id, $wallet_id, $amount_usd);
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
            ->expects('buyCoin')
            ->with('1', 100)
            ->once()
            ->andThrows(new NotFoundHttpException('Coin not found'));


        $this->expectException(NotFoundHttpException::class);

        $this->buyCoinService->execute($coin_id, $wallet_id, $amount_usd);
    }
}
