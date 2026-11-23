<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InventoryRequest extends FormRequest
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
            'nama_gudang' => ['required', 'string', 'max:255'],
            'lokasi_gudang' => ['required', 'string', 'max:255'],
            'kapasitas_stock' => ['required', 'integer', 'min:0'],
        ];
    }
}
