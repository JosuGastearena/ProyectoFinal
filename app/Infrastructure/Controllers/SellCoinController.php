<?php

namespace App\Infrastructure\Controllers;

use App\Application\Coin\SellCoinService;
use Illuminate\Routing\Controller as BaseController;
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
            $this->sellCoinService->execute($request->input("coin_id"), $request->input("wallet_id"), $request->input("amount_usd"));
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
            "status" => "Success"
        ], Response::HTTP_OK);
    }
}
