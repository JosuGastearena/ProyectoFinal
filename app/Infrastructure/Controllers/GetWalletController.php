<?php

namespace App\Infrastructure\Controllers;

use App\Application\GetCoin\GetCoinService;
use App\Application\Wallet\GetWalletService;
use Barryvdh\Debugbar\Controllers\BaseController;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

class GetWalletController extends BaseController
{
    private GetWalletService $getWalletService;

    public function __construct(GetWalletService $getWalletService)
    {
        $this->getWalletService = $getWalletService;
    }

    public function __invoke(string $walletID): JsonResponse
    {
        try {
            $wallet = $this->getWalletService->execute($walletID);
        } catch (ServiceUnavailableHttpException $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        } catch (NotFoundHttpException $exception) {
            return response()->json([
                'error' =>'A wallet with the specified ID was not found'
            ], Response::HTTP_NOT_FOUND);
        }
        $json =[];
        $coins = $wallet->getListCoin();
        for($i = 0; $i<count($coins);$i++){
            $json[] = array(
                "coin_id" => $coins[$i][0]->getCoin_id(),
                "name" => $coins[$i][0]->getName(),
                "symbol" => $coins[$i][0]->getSymbol(),
                "amount" => $coins[$i][1],
                "value_usd" => $coins[$i][0]->getPrice_usd()
            );
        }
        return response()->json($json, Response::HTTP_OK);
    }
}
