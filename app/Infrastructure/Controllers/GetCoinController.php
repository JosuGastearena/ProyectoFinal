<?php

namespace App\Infrastructure\Controllers;

use App\Application\GetCoin\GetCoinService;
use Barryvdh\Debugbar\Controllers\BaseController;
use Exception;
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

    public function __invoke(string $coinID): JsonResponse
    {
        $coin = $this->getCoinService->execute($coinID);

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
