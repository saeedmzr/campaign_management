<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\FindOrderRequest;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Repositories\CurrencyRepository;
use App\Repositories\OrderRepository;
use App\Repositories\RateRepository;
use App\Services\CryptoService;
use App\Services\Exchanger\FCSApi;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;

class OrderController extends BaseController
{

    private $cryptoService;

    public function __construct(
        private OrderRepository    $orderRepository,
        private CurrencyRepository $currencyRepository,
        private RateRepository     $rateRepository,
        private OrderService       $orderService
    )
    {
        $this->cryptoService = new CryptoService(new FCSApi(), $this->currencyRepository, $this->rateRepository);
    }

    /**
     * @OA\Get(
     *     path="/orders",
     *     summary="Get a order by tracking_code",
     *     description="Retrieves a single order identified by its tracking_code.",
     *     tags={"order Management"},
     *     @OA\Parameter(
     *         name="tracking_code",
     *         in="query",
     *         description="tracking_code of the order",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/OrderSchema"),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order not found"
     *     )
     * )
     */
    public function show(FindOrderRequest $request): JsonResponse
    {

        try {
            $order = $this->orderRepository->getOrderbyTrackingCode($request->tracking_code);
            return self::successResponse(
                new OrderResource($order),
                'Order show has been generated successfully.',
            );
        } catch (\Exception $exception) {
            return self::errorResponse([], $exception->getMessage());
        }

    }

    /**
     * @OA\Post(
     *     path="/orders",
     *     summary="Create a new order",
     *     description="Creates a new order with the provided details.",
     *               tags={"order Management"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CreateOrderSchema")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="order created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", ref="#/components/schemas/OrderSchema"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors"
     *     )
     * )
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {

        try {
            $order = $this->orderService->createOrder($this->cryptoService, $request->validated());

            return self::successResponse(
                new orderResource($order),
                'Order has been created successfully.', 201);
        } catch (\Exception $e) {
            return self::errorResponse([], $e->getMessage());
        }

    }

}
