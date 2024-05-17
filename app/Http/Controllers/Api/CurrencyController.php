<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\FindOrderRequest;
use App\Http\Requests\GetRateRequest;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\CurrencyResource;
use App\Http\Resources\RateResource;
use App\Http\Resources\OrderResource;
use App\Repositories\CurrencyRepository;
use App\Repositories\OrderRepository;
use App\Repositories\RateRepository;
use App\Services\CryptoService;
use App\Services\Exchanger\FCSApi;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;

class CurrencyController extends BaseController
{

    private $cryptoService;

    public function __construct(
        private CurrencyRepository $currencyRepository,
        private RateRepository     $rateRepository
    )
    {
        $this->cryptoService = new CryptoService(new FCSApi(), $this->currencyRepository, $this->rateRepository);
    }

    /**
     * @OA\Get(
     *     path="/currencies",
     *     summary="Get currencies list",
     *     description="Retrieves currencies list.",
     *     tags={"currency Management"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/CurrencySchema"),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order not found"
     *     )
     * )
     */
    public function index(): JsonResponse
    {

        try {
            $currencies = $this->currencyRepository->all();
            return self::successResponse(
                CurrencyResource::collection($currencies),
                'Currencies list generated successfully.',
            );
        } catch (\Exception $exception) {
            return self::errorResponse([], $exception->getMessage());
        }

    }

    /**
     * @OA\Get(
     *     path="/rates",
     *     summary="Get a rate of tow symbol",
     *     description="Retrieves  rate of tow symbol.",
     *     tags={"currency Management"},
     *     @OA\Parameter(
     *         name="first_symbol",
     *         in="query",
     *         description="first currency symbol",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *          @OA\Parameter(
     *          name="second_symbol",
     *          in="query",
     *          description="second currency symbol",
     *          required=true,
     *          @OA\Schema(type="string")
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/RateSchema"),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Rate not found"
     *     )
     * )
     */

    public function rate(GetRateRequest $request): JsonResponse
    {


        try {
            $price = $this->cryptoService->rateReader($request->first_symbol, $request->second_symbol);
            return self::successResponse(
                new RateResource($price),
                'Rate generated successfully.',
            );
        } catch (\Exception $exception) {
            return self::errorResponse([], $exception->getMessage());
        }

    }
}
