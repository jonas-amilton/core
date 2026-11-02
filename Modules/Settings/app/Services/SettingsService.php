<?php

declare(strict_types=1);

namespace Modules\Settings\Services;

use Modules\Settings\Contracts\SettingsRepository;
use Modules\Settings\Data\SettingsData;

final class SettingsService
{
    /**
     * @var array<string, string>|null
     */
    private ?array $cache = null;

    public function __construct(private SettingsRepository $settings) {}

    /**
     * @return array<string, string>
     */
    public function all(): array
    {
        return $this->cache ??= array_replace(
            SettingsData::DEFAULTS,
            $this->settings->values(),
        );
    }

    public function get(string $key): string
    {
        return $this->all()[$key] ?? '';
    }

    /**
     * @param  array<string, string>  $values
     */
    public function update(array $values): void
    {
        $this->settings->put(array_filter(
            array_intersect_key($values, SettingsData::DEFAULTS),
            fn (string $value): bool => $value !== '',
        ));
        $this->cache = null;
    }
}
