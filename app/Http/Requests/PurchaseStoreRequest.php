<?php

namespace App\Http\Requests;

use App\Enums\PaymentType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PurchaseStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "payment_type" => ['required', Rule::in(PaymentType::values())],
            "items" => 'required|array|min:1',
            "items.*.item_id" => 'required|exists:items,id',
            "items.*.item_quantity" => 'required|integer|min:1',
            'address.cep' => 'required|size:8',
            'address.number' => 'required|numeric',

            'payment_data.card_number' => 'min:13|max:16|required_if:payment_type,' . PaymentType::CREDIT_CARD,
            'payment_data.expiry_date' => 'required_if:payment_type,' . PaymentType::CREDIT_CARD,
            'payment_data.cvv' => '|min:3|max:4|required_if:payment_type,' . PaymentType::CREDIT_CARD,
        ];
    }
}
