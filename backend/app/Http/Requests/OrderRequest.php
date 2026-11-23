<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'product_id' => ['required', 'uuid', 'exists:products,id'],
            'customer_id' => ['required', 'uuid', 'exists:customers,id'],
            'tanggal_pemesanan' => ['required', 'date'],
            'jumlah_barang' => ['required', 'integer', 'min:1'],
            'status_pemesanan' => ['required', 'string', 'max:50'],
        ];
    }
}
