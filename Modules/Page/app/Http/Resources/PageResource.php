<?php

declare(strict_types=1);

namespace Modules\Page\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Page\Models\Page;

final class PageResource extends JsonResource
{
    private Page $page;

    public function __construct(Page $resource)
    {
        parent::__construct($resource);

        $this->page = $resource;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->page->toEditorArray();
    }
}
