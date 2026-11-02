<?php

declare(strict_types=1);

namespace Modules\Content\Http\Requests;

use App\Support\TranslationInput;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Content\Data\FaqData;

final class FaqRequest extends FormRequest
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
            'question' => ['required', 'string', 'max:200'],
            'answer' => ['required', 'string', 'max:5000'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'translations' => ['nullable', 'array'],
            'translations.*.question' => ['nullable', 'string', 'max:200'],
            'translations.*.answer' => ['nullable', 'string', 'max:5000'],
        ];
    }

    public function toData(): FaqData
    {
        return FaqData::fromArray([
            ...$this->validated(),
            'translations' => TranslationInput::fromArray($this->input('translations'), ['question', 'answer']),
        ]);
    }
}
