<?php

namespace Tests\app\Application\OpenWallet;

use App\Application\CryptoCurrenciesDataSource\CryptoCurrenciesDataSource;
use App\Application\Wallet\OpenWalletService;
use App\Domain\Coin;
use App\Domain\CryptoCurrenciesCache;
use App\Domain\Wallet;
use Mockery;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

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
        $wallet = new Wallet("1", []);
        $this->cryptoCurrenciesDataSource
            ->expects('openWallet')
            ->once()
            ->andReturn($wallet);

        $expectedWallet = $this->openWalletService->execute();
        $this->assertEquals($wallet, $expectedWallet);
    }

    /**
     * @test
     */
    public function serviceUnavailable()
    {
        $wallet = new Wallet("1", []);
        $this->cryptoCurrenciesDataSource
            ->expects('openWallet')
            ->once()
            ->andThrows(new ServiceUnavailableHttpException(0, 'Service unavailable'));

        $this->expectException(ServiceUnavailableHttpException::class);

        $this->openWalletService->execute();
    }

    /**
     * @test
     */
    public function returnWalletCacheTest()
    {
        $coin = new Coin("1", "*", "Crypt", "1", 1, "100");
        $coin2 = new Coin("2", "€", "Crypt2", "2", 2, "1000");

        $cache = new CryptoCurrenciesCache();

        $wallet = $cache->openWallet();

        $wallet->addCoin($coin, 4);
        $wallet->addCoin($coin2, 2);

        $cache->set($wallet);

        $wallet2 = $cache->get($wallet->getWalletId());

        $this->assertEquals($wallet, $wallet2);

        print_r($wallet2->getListCoin());
    }
}
