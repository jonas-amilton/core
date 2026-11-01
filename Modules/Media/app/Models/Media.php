<?php

declare(strict_types=1);

namespace Modules\Media\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Modules\Media\Enums\MediaKind;

/**
 * @property int $id
 * @property string $disk
 * @property string $path
 * @property string $original_name
 * @property string $mime
 * @property int $size
 * @property int|null $width
 * @property int|null $height
 * @property string|null $alt
 * @property string|null $caption
 * @property string|null $hash
 * @property Carbon|null $created_at
 */
class Media extends Model
{
    protected $table = 'media';

    protected $fillable = [
        'uploaded_by',
        'disk',
        'path',
        'original_name',
        'mime',
        'size',
        'width',
        'height',
        'alt',
        'caption',
        'hash',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'size' => 'integer',
            'width' => 'integer',
            'height' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function url(): string
    {
        return Storage::disk($this->disk)->url($this->path);
    }

    public function kind(): MediaKind
    {
        return MediaKind::fromMime($this->mime);
    }

    /**
     * @return array<string, mixed>
     */
    public function toAdminArray(): array
    {
        return [
            'id' => $this->id,
            'url' => $this->url(),
            'original_name' => $this->original_name,
            'mime' => $this->mime,
            'kind' => $this->kind()->value,
            'size' => $this->size,
            'width' => $this->width,
            'height' => $this->height,
            'alt' => $this->alt,
            'caption' => $this->caption,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
