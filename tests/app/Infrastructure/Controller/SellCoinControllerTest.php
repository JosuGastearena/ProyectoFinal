<?php

namespace Tests\app\Infrastructure\Controller;

use App\Application\CryptoCurrenciesDataSource\CryptoCurrenciesDataSource;
use App\Application\CryptoCurrenciesDataSource\CurrenciesDataSource;
use App\Domain\Coin;
use App\Domain\Wallet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Mockery;
use PHPUnit\Util\Json;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Tests\TestCase;
use Exception;

class SellCoinControllerTest extends TestCase
{
    private CryptoCurrenciesDataSource $cryptoCurrenciesDataSource;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->cryptoCurrenciesDataSource = Mockery::mock(CryptoCurrenciesDataSource::class);
        $this->app->bind(CryptoCurrenciesDataSource::class, fn () => $this->cryptoCurrenciesDataSource);
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
        $wallet->addCoin($coin, $amount_usd);

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

        $response = $this->postJson('/api/coin/sell', ["coin_id" => $coin_id,
            "wallet_id" => $wallet_id,
            "amount_usd" => $amount_usd]);

        $response->assertStatus(Response::HTTP_OK)->assertExactJson([
            'status' => "Success"
        ]);
    }
}
