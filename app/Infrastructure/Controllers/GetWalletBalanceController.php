<?php

namespace App\Infrastructure\Controllers;

use App\Application\GetCoin\GetCoinService;
use App\Application\Wallet\GetWalletBalanceService;
use App\Application\Wallet\GetWalletService;
use Barryvdh\Debugbar\Controllers\BaseController;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

class GetWalletBalanceController extends BaseController
{
    private GetWalletBalanceService $getWalletBalanceService;

    public function __construct(GetWalletBalanceService $getWalletBalanceService)
    {
        $this->getWalletBalanceService = $getWalletBalanceService;
    }

    public function __invoke(string $walletID): JsonResponse
    {
        $balance = $this->getWalletBalanceService->execute($walletID);
        return response()->json([
            "balance_usd" => $balance
        ], Response::HTTP_OK);
    }
}
