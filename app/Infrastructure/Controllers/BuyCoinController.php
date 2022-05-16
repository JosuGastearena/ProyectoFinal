<?php

namespace App\Infrastructure\Controllers;

use App\Application\GetCoin\GetCoinService;
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
                'error' =>'coin_id not introduced'
            ], Response::HTTP_BAD_REQUEST);
        } elseif(!$request->has("wallet_id")) {
            return response()->json([
                'error' =>'wallet_id not introduced'
            ], Response::HTTP_BAD_REQUEST);
        } elseif(!$request->has("amount_usd")) {
            return response()->json([
                'error' => 'amount_usd not introduced'
            ], Response::HTTP_BAD_REQUEST);
        }
        return response()->json([
            'bien' => 'bien'
        ], Response::HTTP_ACCEPTED);
        /*
        try {
            $coin = $this->buyCoinService->execute($request);
        } catch (ServiceUnavailableHttpException $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        } catch (NotFoundHttpException $exception) {
            return response()->json([
                'error' =>'A coin with the specified ID was not found'
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json([
            'coin_id' => $coin->getCoin_id(),
            'symbol' => $coin->getSymbol(),
            'name' => $coin->getName(),
            'name_id' => $coin->getName_id(),
            'rank' => $coin->getRank(),
            'price_usd' => $coin->getPrice_usd()
        ], Response::HTTP_OK);
        */
    }
}
