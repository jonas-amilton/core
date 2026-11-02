<?php

declare(strict_types=1);

namespace Modules\Settings\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Settings\Data\SettingsData;

final class UpdateSettingsRequest extends FormRequest
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
        $rules = [];

        foreach (array_keys(SettingsData::DEFAULTS) as $key) {
            $rules[$key] = ['nullable', 'string', 'max:500'];
        }

        return $rules;
    }

    /**
     * @return array<string, string>
     */
    public function settings(): array
    {
        $values = [];

        foreach (array_keys(SettingsData::DEFAULTS) as $key) {
            $values[$key] = trim((string) $this->input($key, ''));
        }

        return $values;
    }
}
