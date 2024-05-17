<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;


/**
 * @OA\Schema(
 *     schema="CreateOrderSchema",
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         description="order's email address"
 *     ),
 *     @OA\Property(
 *         property="first_symbol",
 *         type="string",
 *         description="currency to get"
 *     ),
 *     @OA\Property(
 *         property="second_symbol",
 *         type="string",
 *         description="currency to pay"
 *     ),
 *    @OA\Property(
 *          property="amount",
 *          type="integer",
 *          description="amount of second_symbol to pay and first_symbol to receive"
 *      ),
 * )
 */
class StoreOrderRequest extends FormRequest
{
    public function rules(): array
    {

        return [
            'email' => 'required|string|email',
            'first_symbol' => 'required|exists:currencies,symbol',
            'second_symbol' => 'required|exists:currencies,symbol|distinct:first_symbol',
            'amount' => 'required',
        ];
    }
}
