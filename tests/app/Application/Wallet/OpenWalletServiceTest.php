<?php

namespace Tests\app\Application\GetCoin;

use App\Application\CryptoCurrenciesDataSource\CryptoCurrenciesDataSource;
use App\Application\CryptoCurrenciesDataSource\CurrenciesDataSource;
use App\Application\GetCoin\GetCoinService;
use App\Application\Wallet\OpenWalletService;
use App\Domain\Coin;
use App\Domain\Wallet;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Tests\TestCase;
use Mockery;

class OpenWalletServiceTest extends TestCase
{
    private OpenWalletService $openWalletService;
    private CryptoCurrenciesDataSource $cryptoCurrenciesDataSource;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->cryptoCurrenciesDataSource = Mockery::mock(CryptoCurrenciesDataSource::class);
        $this->openWalletService = new OpenWalletService($this->cryptoCurrenciesDataSource);
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

        $expectedWallet = $this->openWalletService->execute();
        $this->assertEquals($wallet->getWalletId(), $expectedWallet->getWalletId());
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

        $this->expectException(ServiceUnavailableHttpException::class);

        $this->getCoinService->execute('2');
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

        $this->expectException(NotFoundHttpException::class);

        $this->getCoinService->execute('2');
    }
     * */
}
