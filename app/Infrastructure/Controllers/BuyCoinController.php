<?php

namespace App\Infrastructure\Controllers;

use App\Application\BuyCoin\BuyCoinService;
use App\Application\GetCoin\GetCoinService;
use App\Application\Wallet\GetWalletService;
use Barryvdh\Debugbar\Controllers\BaseController;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

class BuyCoinController extends BaseController
{
    private BuyCoinService $buyCoinService;

    public function __construct(BuyCoinService $buyCoinService)
    {
        $this->buyCoinService = $buyCoinService;
    }

    public function __invoke(Request $request): JsonResponse
    {
        if (!$request->has("coin_id")) {
            return response()->json([
                'error' => 'coin_id not introduced'
            ], Response::HTTP_BAD_REQUEST);
        } elseif (!$request->has("wallet_id")) {
            return response()->json([
                'error' => 'wallet_id not introduced'
            ], Response::HTTP_BAD_REQUEST);
        } elseif (!$request->has("amount_usd")) {
            return response()->json([
                'error' => 'amount_usd not introduced'
            ], Response::HTTP_BAD_REQUEST);
        }
        try {
            $this->buyCoinService->execute($request->input("coin_id"), $request->input("wallet_id"), $request->input("amount_usd"));
        } catch (ServiceUnavailableHttpException $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        } catch (NotFoundHttpException $exception) {
            return response()->json([
                'error' => 'A coin with the specified ID was not found'
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json([
            "Status" => "success"
        ], Response::HTTP_OK);
    }
}
