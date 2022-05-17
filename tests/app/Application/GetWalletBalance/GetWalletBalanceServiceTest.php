<?php

namespace Tests\app\Application\GetWalletBalance;

use App\Application\CryptoCurrenciesDataSource\CryptoCurrenciesDataSource;
use App\Application\CryptoCurrenciesDataSource\CurrenciesDataSource;
use App\Application\Wallet\GetWalletBalanceService;
use App\Application\Wallet\GetWalletService;
use App\Application\Wallet\OpenWalletService;
use App\Domain\Coin;
use App\Domain\CryptoCurrenciesCache;
use App\Domain\Wallet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Mockery;
use PHPUnit\Util\Json;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Tests\TestCase;
use Exception;

class GetWalletBalanceServiceTest extends TestCase
{
    private GetWalletBalanceService $getWalletBalanceService;
    private CryptoCurrenciesDataSource $cryptoCurrenciesDataSource;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->cryptoCurrenciesDataSource = Mockery::mock(CryptoCurrenciesDataSource::class);
        $this->getWalletBalanceService = new GetWalletBalanceService($this->cryptoCurrenciesDataSource);
    }

    /**
     * @test
     */
    public function returnWalletBalance()
    {
        $coin = new Coin("1", "*", "Crypt", "1", 1, "100");
        $coin2 = new Coin("2", "€", "Crypt2", "2", 2, "1000");

        $wallet = new Wallet('1', []);
        $wallet->addCoin($coin, 4);
        $wallet->addCoin($coin2, 2);

        $this->cryptoCurrenciesDataSource
            ->expects('getsWalletBalance')
            ->with('1')
            ->once()
            ->andReturn($wallet->getBalance());
        $expectedBalance = $this->getWalletBalanceService->execute('1');
        $this->assertEquals($wallet->getBalance(), $expectedBalance);
    }

    /**
     * @test
     */
    public function serviceUnavailable()
    {
        $this->cryptoCurrenciesDataSource
            ->expects('getsWalletBalance')
            ->with('1')
            ->once()
            ->andThrows(new ServiceUnavailableHttpException(0, 'Service unavailable'));

        $this->expectException(ServiceUnavailableHttpException::class);

        $this->getWalletBalanceService->execute('1');
    }

    /**
     * @test
     */
    public function walletNotFound()
    {
        $this->cryptoCurrenciesDataSource
            ->expects('getsWalletBalance')
            ->with('1')
            ->once()
            ->andThrows(new NotFoundHttpException('Coin not found'));

        $this->expectException(NotFoundHttpException::class);

        $this->getWalletBalanceService->execute('1');
    }
}
