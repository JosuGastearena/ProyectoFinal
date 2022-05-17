<?php

namespace Tests\app\Application\GetWallet;

use App\Application\CryptoCurrenciesDataSource\CryptoCurrenciesDataSource;
use App\Application\Wallet\GetWalletService;
use App\Domain\Coin;
use App\Domain\Wallet;
use Mockery;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Tests\TestCase;

class GetWalletServiceTest extends TestCase
{
    private GetWalletService $getWalletService;
    private CryptoCurrenciesDataSource $cryptoCurrenciesDataSource;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->cryptoCurrenciesDataSource = Mockery::mock(CryptoCurrenciesDataSource::class);
        $this->getWalletService = new GetWalletService($this->cryptoCurrenciesDataSource);
    }

    /**
     * @test
     */
    public function returnWallet()
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
        $expectedWallet = $this->getWalletService->execute('1');
        $this->assertEquals($wallet, $expectedWallet);
    }

    /**
     * @test
     */
    public function serviceUnavailable()
    {
        $this->cryptoCurrenciesDataSource
            ->expects('getsWalletCryptocurrencies')
            ->with('1')
            ->once()
            ->andThrows(new ServiceUnavailableHttpException(0, 'Service unavailable'));

        $this->expectException(ServiceUnavailableHttpException::class);

        $this->getWalletService->execute('1');
    }

    /**
     * @test
     */
    public function walletNotFound()
    {
        $this->cryptoCurrenciesDataSource
            ->expects('getsWalletCryptocurrencies')
            ->with('1')
            ->once()
            ->andThrows(new NotFoundHttpException('Coin not found'));

        $this->expectException(NotFoundHttpException::class);

        $this->getWalletService->execute('1');
    }
}
