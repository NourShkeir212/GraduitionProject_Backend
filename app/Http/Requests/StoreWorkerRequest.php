<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWorkerRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255', 'email', 'unique:workers'],
            'phone' => ['required', 'regex:/^9[0-9]{8}$/'],
            'password' => ['required', 'confirmed', 'min:8'],
            'gender' => ['required'],
            'category' => ['required', Rule::exists('categories', 'category_name')],
        ];
    }
}
