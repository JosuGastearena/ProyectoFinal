<?php

namespace Tests\app\Application\OpenWallet;

use App\Application\CryptoCurrenciesDataSource\CryptoCurrenciesDataSource;
use App\Application\Wallet\OpenWalletService;
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

}
