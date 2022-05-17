<?php

namespace Tests\app\Infrastructure\Controller;

use App\Application\CryptoCurrenciesDataSource\CryptoCurrenciesDataSource;
use App\Domain\Coin;
use App\Domain\Wallet;
use Illuminate\Http\Response;
use Mockery;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Tests\TestCase;

class GetWalletControllerTest extends TestCase
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
    public function walletGivenWhenIDIntroduced()
    {
        $coin = new Coin("1", "*", "Crypt", "1", 1, "100");
        $coin2 = new Coin("2", "€", "Crypt2", "2", 2, "1000");

        $wallet = new Wallet('1', []);
        $wallet->addCoin($coin, 4);
        $wallet->addCoin($coin2, 2);

        $this->cryptoCurrenciesDataSource
            ->expects('getsWalletCryptocurrencies')
            ->with('1')
            ->once()
            ->andReturn($wallet);

        $response = $this->get('/api/wallet/1');

        $response->assertStatus(Response::HTTP_OK)->assertExactJson([
            [
                "coin_id" => $coin->getCoin_id(),
                "name" => $coin->getName(),
                "symbol" => $coin->getSymbol(),
                "amount" => 4,
                "value_usd" => $coin->getPrice_usd()
            ],
            [
                "coin_id" => $coin2->getCoin_id(),
                "name" => $coin2->getName(),
                "symbol" => $coin2->getSymbol(),
                "amount" => 2,
                "value_usd" => $coin2->getPrice_usd()
            ]
        ]);
    }

    /**
     * @test
     */
    public function serviceUnavailableWhenIDIntroduced()
    {
        $this->cryptoCurrenciesDataSource
            ->expects('getsWalletCryptocurrencies')
            ->with('1')
            ->once()
            ->andThrows(new ServiceUnavailableHttpException(0, 'Service unavailable'));

        $response = $this->get('/api/wallet/1');

        $response->assertStatus(Response::HTTP_SERVICE_UNAVAILABLE)->assertExactJson(['error' => 'Service unavailable']);
    }

    /**
     * @test
     */
    public function walletNotFoundWhenIDIntroduced()
    {
        $this->cryptoCurrenciesDataSource
            ->expects('getsWalletCryptocurrencies')
            ->with('1')
            ->once()
            ->andThrows(new NotFoundHttpException('Coin not found'));

        $response = $this->get('/api/wallet/1');

        $response->assertStatus(Response::HTTP_NOT_FOUND)->assertExactJson(['error' => 'A wallet with the specified ID was not found']);
    }
}
