<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Database\Eloquent\Model;

/**
 * Maintains a folded `search_text` column (lowercase, no accents) so the site
 * search can use plain LIKE across SQLite and Postgres.
 */
trait Searchable
{
    public static function bootSearchable(): void
    {
        static::saving(function (Model $model): void {
            /** @var Model&static $model */
            $model->setAttribute(
                'search_text',
                SearchTerm::fold($model->searchableText()),
            );
        });
    }

    abstract protected function searchableText(): string;
}
