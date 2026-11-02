<?php

declare(strict_types=1);

namespace Modules\Settings\Contracts;

interface SettingsRepository
{
    /**
     * @return array<string, string>
     */
    public function values(): array;

    /**
     * @param  array<string, string>  $values
     */
    public function put(array $values): void;
}
