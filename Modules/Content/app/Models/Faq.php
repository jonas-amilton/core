<?php

declare(strict_types=1);

namespace Modules\Content\Models;

use App\Support\Translatable;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $question
 * @property string $answer
 * @property array<string, array<string, mixed>>|null $translations
 * @property int $sort_order
 */
class Faq extends Model
{
    use Translatable;

    protected $fillable = [
        'question',
        'answer',
        'sort_order',
        'translations',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'translations' => 'array',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toAdminArray(): array
    {
        return [
            'id' => $this->id,
            'question' => $this->question,
            'answer' => $this->answer,
            'sort_order' => $this->sort_order,
            'translations' => $this->editorTranslations(['question', 'answer']),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toCardArray(): array
    {
        return [
            'title' => $this->transString('question') ?? $this->question,
            'description' => $this->transString('answer') ?? $this->answer,
        ];
    }
}
