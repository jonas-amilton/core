<?php

declare(strict_types=1);

namespace Modules\Quote\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;
use Modules\Quote\Contracts\QuoteRepository;
use Modules\Quote\Models\Quote;

final class QuoteService
{
    public function __construct(
        private QuoteRepository $quotes,
        private Cart $cart,
    ) {}

    /**
     * @return Collection<int, Quote>
     */
    public function quotes(): Collection
    {
        return $this->quotes->quotes();
    }

    /**
     * @param  array{name: string, email: string, phone: string|null, message: string|null}  $data
     */
    public function submit(array $data): Quote
    {
        $items = array_map(
            fn (array $item): array => [
                'product_id' => $item['product']->id,
                'name' => $item['product']->name,
                'quantity' => $item['quantity'],
            ],
            $this->cart->items(),
        );

        if ($items === []) {
            throw ValidationException::withMessages([
                'cart' => 'Adicione ao menos um produto ao orçamento.',
            ]);
        }

        $quote = $this->quotes->create($data, $items);
        $this->cart->clear();

        return $quote;
    }

    public function markAnswered(Quote $quote, bool $answered): Quote
    {
        return $this->quotes->markAnswered($quote, $answered);
    }

    public function delete(Quote $quote): void
    {
        $this->quotes->delete($quote);
    }
}
