<?php

namespace Tests\app\Infrastructure\Controller;

use App\Application\CryptoCurrenciesDataSource\CryptoCurrenciesDataSource;
use App\Domain\Coin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Mockery;
use PHPUnit\Util\Json;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Tests\TestCase;
use Exception;

class GetCoinControllerTest extends TestCase
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
    public function coinStatusGivenWhenCoinIDIntroduced()
    {
        $coin = new Coin("1", "*", "Crypt", "1", 1, "100");
        $this->cryptoCurrenciesDataSource
            ->expects('coinStatus')
            ->with('1')
            ->once()
            ->andReturn($coin);

        $response = $this->get('/api/coin/status/1');

        $response->assertStatus(Response::HTTP_OK)->assertExactJson([
            'coin_id' => "1",
            'symbol' => "*",
            'name' => "Crypt",
            'name_id' => "1",
            'rank' => 1,
            'price_usd' => "100"
        ]);
    }

    /**
     * @test
     */
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
}
