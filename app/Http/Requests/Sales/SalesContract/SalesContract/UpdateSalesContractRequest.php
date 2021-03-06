<?php

namespace App\Http\Requests\Sales\SalesContract\SalesContract;

use App\Http\Requests\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UpdateSalesContractRequest extends FormRequest
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
    public function rules(Request $request)
    {
        $rulesForm = ValidationRule::form();

        $rulesSalesContract = [
            'customer_id' => ValidationRule::foreignKey('customers'),
            'customer_name' => 'required|string',
            'cash_only' => 'boolean',
            'need_down_payment' => ValidationRule::needDownPayment(),
            'discount_percent' => ValidationRule::discountPercent(),
            'discount_value' => ValidationRule::discountValue(),
            'type_of_tax' => ValidationRule::typeOfTax(),
            'tax' => ValidationRule::tax(),
        ];

        if ($request->has('items')) {
            $rulesSalesContractItem = [
                'items' => 'required|array',
                'items.*.item_id' => ValidationRule::foreignKey('items'),
                'items.*.price' => ValidationRule::price(),
                'items.*.quantity' => ValidationRule::quantity(),
                'items.*.converter' => ValidationRule::converter(),
                'items.*.converter' => ValidationRule::unit(),
                'items.*.allocation_id' => ValidationRule::foreignKeyNullable('allocations'),
            ];

            return array_merge($rulesForm, $rulesSalesContract, $rulesSalesContractItem);
        } else {
            $rulesSalesContractItemGroup = [
                'groups' => 'required|array',
                'groups.*.item_group_id' => ValidationRule::foreignKey('item_groups'),
                'groups.*.price' => ValidationRule::price(),
                'groups.*.quantity' => ValidationRule::quantity(),
                'groups.*.allocation_id' => ValidationRule::foreignKeyNullable('allocations'),
            ];

            return array_merge($rulesForm, $rulesSalesContract, $rulesSalesContractItemGroup);
        }
    }
}
