<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerRequest extends FormRequest
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
        $emailRule = Rule::unique('customers', 'email');

        if ($this->route('customer')) {
            $emailRule->ignore($this->route('customer'));
        }

        return [
            'nama_customer' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', $emailRule],
            'nomor_telepon' => ['required', 'string', 'max:30'],
            'alamat' => ['required', 'string', 'max:255'],
        ];
    }
}
