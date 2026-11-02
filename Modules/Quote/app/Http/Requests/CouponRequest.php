<?php

declare(strict_types=1);

namespace Modules\Quote\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class CouponRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:30', 'unique:coupons,code'],
            'percent' => ['required', 'integer', 'min:1', 'max:100'],
            'expires_at' => ['nullable', 'date'],
        ];
    }
}
