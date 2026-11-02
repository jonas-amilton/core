<?php

declare(strict_types=1);

namespace Modules\Page\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Page\Enums\PageStatus;
use Modules\Page\Models\Page;

/**
 * @extends Factory<Page>
 */
final class PageFactory extends Factory
{
    protected $model = Page::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => 'Página '.fake()->word(),
            'blocks' => [],
            'is_service' => false,
            'status' => PageStatus::Draft,
            'published_at' => null,
        ];
    }
}
