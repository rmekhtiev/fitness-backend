<?php

namespace App\Http\Requests\BarItem;

use App\Enums\Gender;
use App\Enums\PaymentMethod;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;

class BarItemSellRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // todo
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'quantity' => 'required|integer',
            'payment_method' => [
                'required',
                new EnumValue(PaymentMethod::class, true),
            ],
        ];
    }
}
