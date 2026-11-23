<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockInRequest extends FormRequest
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
            'supplier_id' => ['required', 'uuid', 'exists:suppliers,id'],
            'product_id' => ['required', 'uuid', 'exists:products,id'],
            'inventory_id' => ['required', 'uuid', 'exists:inventories,id'],
            'tanggal_masuk' => ['required', 'date'],
            'jumlah_masuk' => ['required', 'integer', 'min:1'],
        ];
    }
}
