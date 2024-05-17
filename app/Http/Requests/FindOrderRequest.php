<?php

namespace App\Http\Requests;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;


class FindOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'tracking_code' => ['required', 'exists:orders,tracking_code',],
        ];
    }
}
