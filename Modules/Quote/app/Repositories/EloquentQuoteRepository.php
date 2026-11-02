<?php

declare(strict_types=1);

namespace Modules\Quote\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Quote\Contracts\QuoteRepository;
use Modules\Quote\Models\Quote;
use Modules\Quote\Models\QuoteItem;

final class EloquentQuoteRepository implements QuoteRepository
{
    /**
     * @return Collection<int, Quote>
     */
    public function quotes(): Collection
    {
        return Quote::query()->with('items')->latest('id')->get();
    }

    /**
     * @param  array{name: string, email: string, phone: string|null, message: string|null}  $data
     * @param  array<int, array{product_id: int|null, name: string, quantity: int, price: string|null}>  $items
     */
    public function create(array $data, array $items): Quote
    {
        $quote = Quote::query()->create($data);

        foreach ($items as $item) {
            QuoteItem::query()->create([
                'quote_id' => $quote->id,
                'product_id' => $item['product_id'],
                'name' => $item['name'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['price'],
            ]);
        }

        return $quote->refresh()->load('items');
    }

    public function delete(Quote $quote): void
    {
        $quote->delete();
    }

    public function markAnswered(Quote $quote, bool $answered): Quote
    {
        $quote->update(['answered_at' => $answered ? now() : null]);

        return $quote->refresh();
    }
}
