<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateWorkerRequest extends FormRequest
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
            'name' => ['string', 'max:255', Rule::unique('workers')->ignore(Auth::user()->id)],
            'email' => ['string', 'max:255', 'email', Rule::unique('workers')->ignore(Auth::user()->id)],
            'phone' => ['regex:/^9[0-9]{8}$/', Rule::unique('workers')->ignore(Auth::user()->id)],
            'availability' => ['in:available,unavailable'],
            'address' => ['string', 'max:255'],
            'category' => [Rule::exists('categories', 'category_name')],
        ];
    }

    public function messages()
    {
        return [
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name may not be greater than 255 characters.',
            'email.string' => 'The email must be a string.',
            'email.max' => 'The email may not be greater than 255 characters.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'The email has already been taken.',
            'phone.regex' => 'The phone number must start with 9 and followed by 8 digits.',
            'availability.in' => 'The availability must be either available or unavailable.',
            'address.string' => 'The address must be a string.',
            'address.max' => 'The address may not be greater than 255 characters.',
            'category.exists' => 'The selected category is invalid.',
        ];
    }
}
