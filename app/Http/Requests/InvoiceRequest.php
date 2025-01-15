<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceRequest extends FormRequest
{
    // ...existing code...

    public function rules()
    {
        return [
            'company_id' => 'required|exists:companies,id',
            'customer_id' => 'required|exists:customers,id',
            'subject' => 'required|string|max:255',
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.amount' => 'required|numeric|min:0',
            'issued_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issued_date',
            'sub_total' => 'required|numeric|min:0',
            'tax' => 'required|numeric|min:0',
            'grand_total' => 'required|numeric|min:0',
        ];
    }

    // ...existing code...
}
