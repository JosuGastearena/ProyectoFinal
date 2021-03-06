<?php

namespace App\Infrastructure\Controllers;

use App\Application\Wallet\OpenWalletService;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
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
        try {
            $wallet = $this->openWalletService->execute();
        } catch (ServiceUnavailableHttpException $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        }
        return response()->json([
            'wallet_id' => $wallet->getWalletId()
        ], Response::HTTP_OK);
    }
}
