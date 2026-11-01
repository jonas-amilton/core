<?php

declare(strict_types=1);

namespace Modules\Page\Http\Requests;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Foundation\Http\FormRequest;

final class PublishPageRequest extends FormRequest
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
            'published_at' => ['nullable', 'date'],
        ];
    }

    public function publishedAt(): ?CarbonInterface
    {
        $value = $this->input('published_at');

        return is_string($value) && $value !== '' ? Carbon::parse($value) : null;
    }
}
