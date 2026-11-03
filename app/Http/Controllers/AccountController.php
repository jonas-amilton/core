<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Page\Services\SiteNavigation;
use Modules\Quote\Models\Quote;

final class AccountController
{
    public function __invoke(Request $request, SiteNavigation $navigation): Response
    {
        $orders = Quote::query()
            ->with('items')
            ->where('user_id', $request->user()?->id)
            ->latest('id')
            ->get()
            ->map(function (Quote $quote): array {
                $items = $quote->items->map(fn ($item): array => [
                    'name' => $item->name,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                ])->values()->all();

                $total = $quote->items->sum(
                    fn ($item): float => (float) ($item->unit_price ?? 0) * $item->quantity,
                ) - (float) $quote->discount;

                return [
                    'id' => $quote->id,
                    'created_at' => $quote->created_at?->toIso8601String(),
                    'answered' => $quote->answered_at !== null,
                    'items' => $items,
                    'total' => $total,
                ];
            })
            ->all();

        $favorites = $request->user()?->favorites()->with('media')->get()
            ->map(fn ($product): array => $product->toSiteArray())
            ->values()
            ->all() ?? [];

        return Inertia::render('Site::Account', [
            'orders' => $orders,
            'favorites' => $favorites,
            'navigation' => $navigation->items(),
        ]);
    }
}
