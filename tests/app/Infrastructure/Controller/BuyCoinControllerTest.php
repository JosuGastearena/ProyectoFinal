<?php

namespace Tests\app\Infrastructure\Controller;

use App\Application\CryptoCurrenciesDataSource\CryptoCurrenciesDataSource;
use App\Application\CryptoCurrenciesDataSource\CurrenciesDataSource;
use App\Domain\Coin;
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
    public function ExceptionGivenWhenCoinIDNotIntroduced()
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
    public function ExceptionGivenWhenWalletIDNotIntroduced()
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
    public function ExceptionGivenWhenAmountUSDNotIntroduced()
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
