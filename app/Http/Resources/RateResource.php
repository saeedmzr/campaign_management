<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="RateSchema",
 *     @OA\Property(
 *         property="price",
 *         type="integer",
 *         description="rate price"
 *     ),
 * )
 */
class RateResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'price' => $this['price'],
        ];
    }
}
