<?php

declare(strict_types=1);

namespace Modules\Quote\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class QuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'string', 'email', 'max:180'],
            'phone' => ['nullable', 'string', 'max:40'],
            'message' => ['nullable', 'string', 'max:5000'],
            // Honeypot: hidden field that humans never fill.
            'website' => ['nullable', 'size:0'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Informe seu nome.',
            'email.required' => 'Informe um e-mail para o orçamento.',
            'email.email' => 'Informe um e-mail válido.',
        ];
    }

    /**
     * @return array{name: string, email: string, phone: string|null, message: string|null}
     */
    public function toData(): array
    {
        $phone = trim((string) $this->input('phone', ''));
        $message = trim((string) $this->input('message', ''));

        return [
            'name' => trim((string) $this->input('name')),
            'email' => mb_strtolower(trim((string) $this->input('email'))),
            'phone' => $phone !== '' ? $phone : null,
            'message' => $message !== '' ? $message : null,
        ];
    }
}
