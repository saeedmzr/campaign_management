<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="CurrencySchema",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Currency ID"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Currency name"
 *     ),
 *     @OA\Property(
 *         property="symbol",
 *         type="string",
 *         description="Currency symbol"
 *     ),
 * )
 */
class CurrencyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'symbol' => $this->symbol,
        ];
    }
}
