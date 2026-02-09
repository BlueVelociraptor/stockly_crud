<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Product\Data\SaveProductDTO;
use Illuminate\Foundation\Http\FormRequest;

class SaveProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => "required|string|max:30|unique:products,name",
            "price" => "required|numeric|min:1",
            "image" => "required|image|max:8192|mimes:png,jpg",
            "description" => "nullable|string|max:200",
        ];
    }

    public function toDTO(): SaveProductDTO
    {
        $formData = $this->validated();

        return new SaveProductDTO(
            name: $formData["name"],
            price: $formData["price"],
            image: $this->file("image"),
            description: $formData["description"],
        );
    }
}
