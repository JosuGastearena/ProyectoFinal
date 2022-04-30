<?php

namespace Tests\app\Application\GetCoin;

use App\Application\CryptoCurrenciesDataSource\CryptoCurrenciesDataSource;
use App\Application\GetCoin\GetCoinService;
use App\Domain\Coin;
use Illuminate\Http\Response;
use Tests\TestCase;
use Mockery;

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
}
