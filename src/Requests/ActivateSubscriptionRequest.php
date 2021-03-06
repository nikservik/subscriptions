<?php

namespace Nikservik\Subscriptions\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActivateSubscriptionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'tariff' => 'required|exists:tariffs,id',
        ];
    }

    public function messages()
    {
        return [
            'tariff.required' => 'tariff.required',
            'tariff.exists' => 'tariff.exists',
        ];
    }

}
