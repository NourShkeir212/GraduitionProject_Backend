<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImageRequest extends FormRequest
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
            'profile_image' => ['required','image','mimes:jpeg,png,jpg,bmp,gif','max:4096']
        ];
    }

    /**
     * Get custom messages for validation errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'image.required' => 'Please select an image to upload.',
            'image.image' => 'The selected file must be an image.',
            'image.mimes' => 'The image must be a JPEG, PNG, JPG, or BMP file.',
            'image.max' => 'The image file size must not exceed 6,144 kilobytes.',
        ];
    }
}
