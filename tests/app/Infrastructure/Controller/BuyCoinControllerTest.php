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

class BuyCoinControllerTest extends TestCase
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

        $response = $this->postJson('/api/coin/buy', ["coin_id" => $coin_id,
            "wallet_id" => $wallet_id,
            "amount_usd" => $amount_usd]);

        $response->assertStatus(Response::HTTP_OK)->assertExactJson([
            'status' => "Success"
        ]);
    }

    /**
     * @test
     */
    public function serviceUnavailableWhenIDIntroduced()
    {
        $this->cryptoCurrenciesDataSource
            ->expects('buyCoin')
            ->with('1', 100)
            ->once()
            ->andThrows(new ServiceUnavailableHttpException(0, 'Service unavailable'));

        $response = $this->postJson('/api/coin/buy', ["coin_id" => "1",
            "wallet_id" => "2",
            "amount_usd" => 100]);

        $response->assertStatus(Response::HTTP_SERVICE_UNAVAILABLE)->assertExactJson(['error' => 'Service unavailable']);
    }

    /**
     * @test
     */
    public function coinNotFoundWhenIDIntroduced()
    {
        $this->cryptoCurrenciesDataSource
            ->expects('buyCoin')
            ->with('1', 100)
            ->once()
            ->andThrows(new NotFoundHttpException('Coin not found'));

        $response = $this->postJson('/api/coin/buy', ["coin_id" => "1",
            "wallet_id" => "2",
            "amount_usd" => 100]);

        $response->assertStatus(Response::HTTP_NOT_FOUND)->assertExactJson(['error' => 'A coin with the specified ID was not found']);
    }

    /**
     * @test
     */
    public function exceptionGivenWhenCoinIDNotIntroduced()
    {
        $this->cryptoCurrenciesDataSource
            ->expects('buyCoin')
            ->with('1', 100)
            ->never();


        $response = $this->postJson('/api/coin/buy', [
            "wallet_id" => "2",
            "amount_usd" => 3]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertExactJson(['error' => 'coin_id not introduced']);
    }

    /**
     * @test
     */
    public function exceptionGivenWhenWalletIDNotIntroduced()
    {
        $this->cryptoCurrenciesDataSource
            ->expects('buyCoin')
            ->with('1', 100)
            ->never();


        $response = $this->postJson('/api/coin/buy', ["coin_id" => "1",

            "amount_usd" => 3]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertExactJson(['error' => 'wallet_id not introduced']);
    }

    /**
     * @test
     */
    public function exceptionGivenWhenAmountUSDNotIntroduced()
    {
        $this->cryptoCurrenciesDataSource
            ->expects('buyCoin')
            ->with('1', 100)
            ->never();


        $response = $this->postJson('/api/coin/buy', ["coin_id" => "1",
            "wallet_id" => "2"
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertExactJson(['error' => 'amount_usd not introduced']);
    }

}
