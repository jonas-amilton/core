<?php

declare(strict_types=1);

namespace Modules\Blog\Http\Requests;

use App\Support\TranslationInput;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Blog\Data\PostData;

final class PostRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['nullable', 'string', 'max:200', 'alpha_dash'],
            'category_id' => ['nullable', 'integer', 'exists:blog_categories,id'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'body' => ['required', 'string', 'max:50000'],
            'cover' => ['nullable', 'string', 'max:2048'],
            'published' => ['boolean'],
            'translations' => ['nullable', 'array'],
            'translations.*.title' => ['nullable', 'string', 'max:200'],
            'translations.*.excerpt' => ['nullable', 'string', 'max:500'],
            'translations.*.body' => ['nullable', 'string', 'max:50000'],
        ];
    }

    public function toData(): PostData
    {
        return PostData::fromArray([
            ...$this->validated(),
            'published' => $this->boolean('published'),
            'translations' => TranslationInput::fromArray(
                $this->input('translations'),
                ['title', 'excerpt', 'body'],
            ),
        ]);
    }
}
