<?php

namespace App\Infrastructure\Controllers;

use App\Application\Wallet\GetWalletBalanceService;
use Illuminate\Routing\Controller as BaseController;
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

    public function __invoke(string $wallet_id): JsonResponse
    {
        try {
            $balance = $this->getWalletBalanceService->execute($wallet_id);
        } catch (ServiceUnavailableHttpException $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        } catch (NotFoundHttpException $exception) {
            return response()->json([
                'error' =>'A wallet with the specified ID was not found'
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json([
            "balance_usd" => $balance
        ], Response::HTTP_OK);
    }
}
