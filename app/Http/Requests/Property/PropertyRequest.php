<?php

namespace App\Http\Requests\Property;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Helpers\ApiResponse;

class PropertyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price_per_night' => 'required|numeric|min:0',
            'location' => 'required|string|max:255',
            'amenities' => 'required|array',
            'images' => 'required|array',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The property title is required.',
            'title.string' => 'The property title must be a valid string.',
            'title.max' => 'The property title must not exceed 255 characters.',

            'description.required' => 'A description of the property is required.',
            'description.string' => 'The description must be a valid string.',

            'price_per_night.required' => 'Please specify the nightly price.',
            'price_per_night.numeric' => 'The price must be a number.',
            'price_per_night.min' => 'The price must be at least 0.',

            'location.required' => 'The property location is required.',
            'location.string' => 'The location must be a valid string.',
            'location.max' => 'The location must not exceed 255 characters.',

            'amenities.required' => 'Please provide at least one amenity.',
            'amenities.array' => 'Amenities must be provided as a list.',

            'images.required' => 'Please upload at least one image of the property.',
            'images.array' => 'Images must be uploaded as a list.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            ApiResponse::error('Validation error.', 422, $validator->errors()->toArray())
        );
    }
}
