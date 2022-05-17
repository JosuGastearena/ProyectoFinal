<?php

namespace App\Infrastructure\Controllers;

use App\Application\BuyCoin\BuyCoinService;
use App\Application\GetCoin\GetCoinService;
use App\Application\SellCoin\SellCoinService;
use App\Application\Wallet\GetWalletService;
use Barryvdh\Debugbar\Controllers\BaseController;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

class SellCoinController extends BaseController
{
    private SellCoinService $sellCoinService;

    public function __construct(SellCoinService $sellCoinService)
    {
        $this->sellCoinService = $sellCoinService;
    }

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $this->sellCoinService->execute($request->input("coin_id"), $request->input("wallet_id"), $request->input("amount_usd"));
        } catch (ServiceUnavailableHttpException $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        }
        return response()->json([
            "status" => "Success"
        ], Response::HTTP_OK);
    }
}
