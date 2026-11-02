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
        $this->setQuantity(
            $productId,
            ($this->quantities()[$productId] ?? 0) + max(1, $quantity),
        );
    }

    /**
     * Clamps to the available stock (null means no stock control).
     */
    public function setQuantity(int $productId, int $quantity): void
    {
        $product = $this->catalog->findProduct($productId);

        if ($product === null) {
            return;
        }

        $limit = $product->stock === null ? 999 : min(999, $product->stock);

        if ($limit <= 0) {
            $this->remove($productId);

            return;
        }

        $items = $this->quantities();
        $items[$productId] = max(1, min($limit, $quantity));
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
