<?php

namespace App\Infrastructure\Controllers;

use App\Application\GetCoin\GetCoinService;
use App\Application\Wallet\OpenWalletService;
use Barryvdh\Debugbar\Controllers\BaseController;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

class OpenWalletController extends BaseController
{
    private OpenWalletService $openWalletService;

    public function __construct(OpenWalletService $openWalletService)
    {
        $this->openWalletService = $openWalletService;
    }

    public function __invoke(): JsonResponse
    {
        $wallet = $this->openWalletService->execute();

        return response()->json([
            'wallet_id' => $wallet->getWalletId()
        ], Response::HTTP_OK);
    }
}
