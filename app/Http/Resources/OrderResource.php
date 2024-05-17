<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="OrderSchema",
 *     @OA\Property(
 *         property="tracking_code",
 *         type="integer",
 *         description="Order tracking_code"
 *     ),
 *     @OA\Property(
 *         property="amount_paid",
 *         type="string",
 *         description="amount_paid"
 *     ),
 *     @OA\Property(
 *         property="amount_received",
 *         type="string",
 *         description="amount_received"
 *     ),
 *      @OA\Property(
 *         property="status",
 *         type="string",
 *         description="status"
 *     ),
 *     @OA\Property(
 *         property="email_address",
 *         type="string",
 *         description="Order email address"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="datetime",
 *         description="Order updated_at datetime"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="datetime",
 *         description="Order created_at datetime"
 *     ),
 * )
 */
class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'tracking_code' => $this->tracking_code,
            'amount_paid' => $this->amount_paid . ': ' . $this->rate->sourceCurrency->symbol ?? '',
            'amount_received' => $this->amount_received . ': ' . $this->rate->currency->symbol ?? '',
            'status' => $this->status,
            'email_address' => $this->email_address,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
