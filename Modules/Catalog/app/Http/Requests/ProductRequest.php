<?php

declare(strict_types=1);

namespace Modules\Catalog\Http\Requests;

use App\Support\TranslationInput;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Catalog\Data\ProductData;

final class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:200'],
            'slug' => ['nullable', 'string', 'max:200', 'alpha_dash'],
            'sku' => ['nullable', 'string', 'max:60'],
            'price' => ['nullable', 'numeric', 'min:0', 'max:9999999'],
            'description' => ['nullable', 'string', 'max:50000'],
            'position' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'category_ids' => ['nullable', 'array'],
            'category_ids.*' => ['integer', 'exists:catalog_categories,id'],
            'media_ids' => ['nullable', 'array'],
            'media_ids.*' => ['integer', 'exists:media,id'],
            'related_ids' => ['nullable', 'array'],
            'related_ids.*' => ['integer', 'exists:catalog_products,id'],
            'translations' => ['nullable', 'array'],
            'translations.*.name' => ['nullable', 'string', 'max:200'],
            'translations.*.description' => ['nullable', 'string', 'max:50000'],
        ];
    }

    public function toData(): ProductData
    {
        return ProductData::fromArray([
            ...$this->validated(),
            'translations' => TranslationInput::fromArray(
                $this->input('translations'),
                ['name', 'description'],
            ),
        ]);
    }
}
