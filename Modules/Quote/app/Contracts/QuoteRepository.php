<?php

declare(strict_types=1);

namespace Modules\Quote\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Modules\Quote\Models\Quote;

interface QuoteRepository
{
    /**
     * @return Collection<int, Quote>
     */
    public function quotes(): Collection;

    /**
     * @param  array{name: string, email: string, phone: string|null, message: string|null, coupon_code?: string|null, discount?: float}  $data
     * @param  array<int, array{product_id: int|null, name: string, quantity: int, price: string|null}>  $items
     */
    public function create(array $data, array $items): Quote;

    public function delete(Quote $quote): void;

    public function markAnswered(Quote $quote, bool $answered): Quote;
}
