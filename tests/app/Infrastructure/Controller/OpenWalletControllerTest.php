<?php

namespace Tests\app\Infrastructure\Controller;

use App\Application\CryptoCurrenciesDataSource\CryptoCurrenciesDataSource;
use App\Domain\Wallet;
use Illuminate\Http\Response;
use Mockery;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Tests\TestCase;

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
        $wallet = new Wallet('1', []);
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
     */
    public function serviceUnavailableWhenOpeningWallet()
    {
        $this->cryptoCurrenciesDataSource
            ->expects('openWallet')
            ->once()
            ->andThrows(new ServiceUnavailableHttpException(0, 'Service unavailable'));

        $response = $this->post('/api/wallet/open');

        $response->assertStatus(Response::HTTP_SERVICE_UNAVAILABLE)->assertExactJson(['error' => 'Service unavailable']);
    }
}
