<?php

namespace Tests\app\Application\GetCoin;

use App\Application\Coin\GetCoinService;
use App\Application\CryptoCurrenciesDataSource\CryptoCurrenciesDataSource;
use App\Domain\Coin;
use Mockery;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Tests\TestCase;

class GetCoinServiceTest extends TestCase
{
    private GetCoinService $getCoinService;
    private CryptoCurrenciesDataSource $cryptoCurrenciesDataSource;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->cryptoCurrenciesDataSource = Mockery::mock(CryptoCurrenciesDataSource::class);
        $this->getCoinService = new GetCoinService($this->cryptoCurrenciesDataSource);
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

        $expectedCoin = $this->getCoinService->execute('1');
        $this->assertEquals($coin, $expectedCoin);
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

        $this->expectException(ServiceUnavailableHttpException::class);

        $this->getCoinService->execute('2');
    }

    /**
     * @test
     */
    public function coinNotFoundWhenIDIntroduced()
    {
        $this->cryptoCurrenciesDataSource
            ->expects('coinStatus')
            ->with('2')
            ->once()
            ->andThrows(new NotFoundHttpException('Coin not found'));

        $this->expectException(NotFoundHttpException::class);

        $this->getCoinService->execute('2');
    }
}
