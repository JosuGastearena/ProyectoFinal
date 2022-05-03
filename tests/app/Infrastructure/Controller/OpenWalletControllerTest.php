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

class OpenWalletControllerTest extends TestCase
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
    public function openNewWallet()
    {
        $wallet = new Wallet('1');
        $this->cryptoCurrenciesDataSource
            ->expects('openWallet')
            ->with()
            ->once()
            ->andReturn($wallet);

        $response = $this->post('/api/wallet/open');

        $response->assertStatus(Response::HTTP_OK)->assertExactJson([
            'wallet_id' => "1"
        ]);
    }

    /**
     * @test
    public function serviceUnavailableWhenIDIntroduced()
    {
        $this->cryptoCurrenciesDataSource
            ->expects('coinStatus')
            ->with('2')
            ->once()
            ->andThrows(new ServiceUnavailableHttpException(0, 'Service unavailable'));

        $response = $this->get('/api/coin/status/2');

        $response->assertStatus(Response::HTTP_SERVICE_UNAVAILABLE)->assertExactJson(['error' => 'Service unavailable']);
    }

     */
    /**
     * @test
    public function coinNotFoundWhenIDIntroduced()
    {
        $this->cryptoCurrenciesDataSource
            ->expects('coinStatus')
            ->with('2')
            ->once()
            ->andThrows(new NotFoundHttpException('Coin not found'));

        $response = $this->get('/api/coin/status/2');

        $response->assertStatus(Response::HTTP_NOT_FOUND)->assertExactJson(['error' => 'A coin with the specified ID was not found']);
    }
     *
     */
}
