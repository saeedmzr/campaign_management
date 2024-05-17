<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;


/**
 * @OA\Schema(
 *     schema="GetRateSchema",
 *     @OA\Property(
 *         property="first_symbol",
 *         type="string",
 *         description="currency first symbol"
 *     ),
 *     @OA\Property(
 *         property="second_symbol",
 *          type="string",
 *          description="currency second symbol"
 *      ),
 * )
 */
class GetRateRequest extends FormRequest
{
    public function rules(): array
    {

        return [
            'first_symbol' => 'required|exists:currencies,symbol',
            'second_symbol' => 'required|exists:currencies,symbol|distinct:first_symbol',
        ];
    }
}
