<?php

declare(strict_types=1);

namespace Modules\Quote\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class CartItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer', 'exists:catalog_products,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:999'],
        ];
    }
}
