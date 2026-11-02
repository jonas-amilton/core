<?php

declare(strict_types=1);

namespace Modules\Page\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Page\Data\PageData;
use Modules\Page\Support\BlockSchema;

abstract class PageRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['nullable', 'string', 'max:200', 'alpha_dash'],
            'blocks' => ['present', 'array'],
            'icon' => ['nullable', 'string', 'max:2048'],
            'is_service' => ['boolean'],
        ];
    }

    public function pageData(): PageData
    {
        /** @var array<int, array<string, mixed>> $blocks */
        $blocks = $this->input('blocks', []);

        return PageData::fromArray([
            'title' => (string) $this->input('title'),
            'slug' => $this->input('slug'),
            'blocks' => BlockSchema::validate($blocks),
            'icon' => $this->input('icon'),
            'is_service' => $this->boolean('is_service'),
        ]);
    }
}
