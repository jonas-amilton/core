<?php

declare(strict_types=1);

namespace Modules\Quote\Services;

use App\Mail\OrderMail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Modules\Catalog\Models\Product;
use Modules\Quote\Contracts\QuoteRepository;
use Modules\Quote\Models\Coupon;
use Modules\Quote\Models\Quote;
use Modules\Settings\Services\SettingsService;
use Throwable;

final class QuoteService
{
    public function __construct(
        private QuoteRepository $quotes,
        private Cart $cart,
        private SettingsService $settings,
    ) {}

    /**
     * @return Collection<int, Quote>
     */
    public function quotes(): Collection
    {
        return $this->quotes->quotes();
    }

    /**
     * @param  array{user_id?: int|null, name: string, email: string, phone: string|null, message: string|null}  $data
     */
    public function submit(array $data): Quote
    {
        $cartItems = $this->cart->items();

        if ($cartItems === []) {
            throw ValidationException::withMessages([
                'cart' => __('site.quote.empty_cart'),
            ]);
        }

        $coupon = $this->cart->couponCode() !== null
            ? Coupon::query()->where('code', $this->cart->couponCode())->first()
            : null;

        $quote = DB::transaction(function () use ($data, $cartItems, $coupon): Quote {
            $items = [];

            foreach ($cartItems as $item) {
                /** @var Product $product */
                $product = Product::query()->lockForUpdate()->findOrFail($item['product']->id);

                if ($product->stock !== null) {
                    if ($product->stock < $item['quantity']) {
                        throw ValidationException::withMessages([
                            'cart' => __('site.quote.insufficient_stock', [
                                'product' => $product->name,
                            ]),
                        ]);
                    }

                    $product->decrement('stock', $item['quantity']);
                }

                $items[] = [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ];
            }

            $subtotal = array_sum(array_map(
                fn (array $item): float => (float) ($item['price'] ?? 0) * $item['quantity'],
                $items,
            ));

            $discount = $coupon !== null && $coupon->isValid()
                ? $coupon->discountFor($subtotal)
                : 0.0;

            $quote = $this->quotes->create([
                ...$data,
                'coupon_code' => $discount > 0 ? $coupon?->code : null,
                'discount' => $discount,
            ], $items);
            $this->cart->clear();

            return $quote;
        });

        $this->notify($quote);

        return $quote;
    }

    private function notify(Quote $quote): void
    {
        $adminEmail = $this->settings->get('email');

        try {
            Mail::to($quote->email)->send(new OrderMail($quote));

            if ($adminEmail !== '' && $adminEmail !== $quote->email) {
                Mail::to($adminEmail)->send(new OrderMail($quote, true));
            }
        } catch (Throwable $exception) {
            report($exception);
        }
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
