<?php

namespace Botble\Marketplace\Http\Requests;

use Botble\Marketplace\Enums\RevenueTypeEnum;
use Botble\Marketplace\Repositories\Interfaces\StoreInterface;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class StoreRevenueRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'type' => Rule::in(RevenueTypeEnum::values()),
            'amount' => 'required|numeric|min:0|not_in:0',
            'description' => 'nullable|max:400',
        ];

        if ($this->input('type') == RevenueTypeEnum::SUBTRACT_AMOUNT) {
            $store = app(StoreInterface::class)->findById($this->route('id'));
            if ($store && $store->customer) {
                $customer = $store->customer;
                $rules['amount'] = 'numeric|min:0|max:' . $customer->balance;
            }
        }

        return $rules;
    }
}
