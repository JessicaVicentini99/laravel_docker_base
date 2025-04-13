<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            "value" => 'required|numeric|min:0,5',
            "payer_id" => 'nullable|integer|exists:users,id',
            "payee_id" => 'required|integer|exists:users,id',
        ];
    }
}
