<?php

declare(strict_types=1);

namespace Modules\Store\Http\Requests;

use App\Support\TranslationInput;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Store\Data\StoreData;

final class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'kind' => ['required', 'in:physical,online'],
            'address' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:60'],
            'url' => ['nullable', 'string', 'max:2048'],
            'position' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'translations' => ['nullable', 'array'],
            'translations.*.name' => ['nullable', 'string', 'max:150'],
            'translations.*.address' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function toData(): StoreData
    {
        return StoreData::fromArray([
            ...$this->validated(),
            'translations' => TranslationInput::fromArray($this->input('translations'), ['name', 'address']),
        ]);
    }
}
