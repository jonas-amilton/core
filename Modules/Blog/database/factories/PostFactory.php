<?php

declare(strict_types=1);

namespace Modules\Blog\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Blog\Models\Post;

/**
 * @extends Factory<Post>
 */
final class PostFactory extends Factory
{
    protected $model = Post::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => null,
            'title' => 'Nota '.fake()->word(),
            'excerpt' => fake()->sentence(),
            'body' => fake()->paragraphs(3, true),
            'cover' => null,
            'published_at' => now()->subDay(),
        ];
    }
}
