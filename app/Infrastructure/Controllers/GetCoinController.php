<?php

namespace App\Infrastructure\Controllers;

use App\Application\Coin\GetCoinService;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

class GetCoinController extends BaseController
{
    private GetCoinService $getCoinService;

    public function __construct(GetCoinService $getCoinService)
    {
        $this->getCoinService = $getCoinService;
    }

    public function __invoke(string $coin_id): JsonResponse
    {
        try {
            $coin = $this->getCoinService->execute($coin_id);
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
    }
}
