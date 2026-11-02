<?php

declare(strict_types=1);

namespace Modules\Content\Http\Requests;

use App\Support\TranslationInput;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Content\Data\TestimonialData;

final class TestimonialRequest extends FormRequest
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
            'author' => ['required', 'string', 'max:120'],
            'role' => ['nullable', 'string', 'max:120'],
            'quote' => ['required', 'string', 'max:2000'],
            'avatar' => ['nullable', 'string', 'max:2048'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'translations' => ['nullable', 'array'],
            'translations.*.role' => ['nullable', 'string', 'max:120'],
            'translations.*.quote' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function toData(): TestimonialData
    {
        return TestimonialData::fromArray([
            ...$this->validated(),
            'translations' => TranslationInput::fromArray($this->input('translations'), ['role', 'quote']),
        ]);
    }
}
