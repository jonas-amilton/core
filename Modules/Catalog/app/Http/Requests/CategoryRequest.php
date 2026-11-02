<?php

declare(strict_types=1);

namespace Modules\Catalog\Http\Requests;

use App\Support\TranslationInput;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Catalog\Data\CategoryData;

final class CategoryRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:120', 'alpha_dash'],
            'parent_id' => ['nullable', 'integer', 'exists:catalog_categories,id'],
            'position' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'translations' => ['nullable', 'array'],
            'translations.*.name' => ['nullable', 'string', 'max:120'],
        ];
    }

    public function toData(): CategoryData
    {
        return CategoryData::fromArray([
            ...$this->validated(),
            'translations' => TranslationInput::fromArray($this->input('translations'), ['name']),
        ]);
    }
}
