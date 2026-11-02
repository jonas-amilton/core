<?php

declare(strict_types=1);

namespace Modules\Quote\Services;

use Modules\Catalog\Contracts\CatalogRepository;
use Modules\Catalog\Models\Product;

/**
 * Session cart: product id => quantity. There is no cart table; the checkout
 * writes the quote with item snapshots.
 */
final class Cart
{
    private const KEY = 'quote.cart';

    public function __construct(private CatalogRepository $catalog) {}

    /**
     * @return array<int, int>
     */
    public function quantities(): array
    {
        /** @var array<int, int> $items */
        $items = session(self::KEY, []);

        return $items;
    }

    /**
     * @return array<int, array{product: Product, quantity: int}>
     */
    public function items(): array
    {
        $items = [];

        foreach ($this->quantities() as $productId => $quantity) {
            $product = $this->catalog->findProduct($productId);

            if ($product !== null) {
                $items[] = ['product' => $product, 'quantity' => $quantity];
            }
        }

        return $items;
    }

    public function count(): int
    {
        return array_sum($this->quantities());
    }

    public function add(int $productId, int $quantity = 1): void
    {
        $items = $this->quantities();
        $items[$productId] = min(999, ($items[$productId] ?? 0) + max(1, $quantity));
        session([self::KEY => $items]);
    }

    public function remove(int $productId): void
    {
        $items = $this->quantities();
        unset($items[$productId]);
        session([self::KEY => $items]);
    }

    public function clear(): void
    {
        session()->forget(self::KEY);
    }
}
