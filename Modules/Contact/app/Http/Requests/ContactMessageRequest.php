<?php

declare(strict_types=1);

namespace Modules\Contact\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class ContactMessageRequest extends FormRequest
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
            'message' => ['required', 'string', 'max:5000'],
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
            'email.required' => 'Informe um e-mail para contato.',
            'email.email' => 'Informe um e-mail válido.',
            'message.required' => 'Escreva sua mensagem.',
        ];
    }

    /**
     * @return array{name: string, email: string, phone: string|null, message: string, kind: string}
     */
    public function toData(): array
    {
        return [
            'name' => trim((string) $this->input('name')),
            'email' => mb_strtolower(trim((string) $this->input('email'))),
            'phone' => ($phone = trim((string) $this->input('phone', ''))) !== '' ? $phone : null,
            'message' => trim((string) $this->input('message')),
            'kind' => 'contact',
        ];
    }
}
