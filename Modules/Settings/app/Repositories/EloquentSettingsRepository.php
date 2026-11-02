<?php

declare(strict_types=1);

namespace Modules\Settings\Repositories;

use Modules\Settings\Contracts\SettingsRepository;
use Modules\Settings\Models\Setting;

final class EloquentSettingsRepository implements SettingsRepository
{
    /**
     * @return array<string, string>
     */
    public function values(): array
    {
        $values = [];

        foreach (Setting::query()->get(['key', 'value']) as $setting) {
            $values[$setting->key] = (string) $setting->value;
        }

        return $values;
    }

    /**
     * @param  array<string, string>  $values
     */
    public function put(array $values): void
    {
        foreach ($values as $key => $value) {
            Setting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
