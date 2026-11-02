<?php

declare(strict_types=1);

namespace Modules\Settings\Data;

/**
 * Fixed key/value form: one source of truth for defaults, validation and the
 * admin screen. Unknown keys are dropped on save.
 */
final class SettingsData
{
    /**
     * @var array<string, string>
     */
    public const DEFAULTS = [
        'site_name' => 'CoreCMS',
        'tagline' => 'Conteúdo, páginas e mídia em um só lugar.',
        'email' => '',
        'phone' => '',
        'whatsapp' => '',
        'address' => '',
        'hours' => '',
        'facebook' => '',
        'instagram' => '',
        'linkedin' => '',
        'stat_years' => '',
        'stat_clients' => '',
        'stat_companies' => '',
        'max_installments' => '0',
        'cookie_text' => 'Este site utiliza cookies para melhorar sua experiência de navegação.',
    ];

    /**
     * @var array<string, array{label: string, type: 'text'|'textarea'}>
     */
    public const FIELDS = [
        'site_name' => ['label' => 'Nome do site', 'type' => 'text'],
        'tagline' => ['label' => 'Assinatura curta', 'type' => 'text'],
        'email' => ['label' => 'E-mail de contato', 'type' => 'text'],
        'phone' => ['label' => 'Telefone', 'type' => 'text'],
        'whatsapp' => ['label' => 'WhatsApp (somente números)', 'type' => 'text'],
        'address' => ['label' => 'Endereço', 'type' => 'text'],
        'hours' => ['label' => 'Horário de atendimento', 'type' => 'text'],
        'facebook' => ['label' => 'Facebook (URL)', 'type' => 'text'],
        'instagram' => ['label' => 'Instagram (URL)', 'type' => 'text'],
        'linkedin' => ['label' => 'LinkedIn (URL)', 'type' => 'text'],
        'stat_years' => ['label' => 'Contador: anos de mercado', 'type' => 'text'],
        'stat_clients' => ['label' => 'Contador: clientes atendidos', 'type' => 'text'],
        'stat_companies' => ['label' => 'Contador: empresas criadas', 'type' => 'text'],
        'max_installments' => ['label' => 'Parcelamento máximo (0 desativa)', 'type' => 'text'],
        'cookie_text' => ['label' => 'Texto do aviso de cookies (LGPD)', 'type' => 'textarea'],
    ];

    /**
     * @return array<int, array{key: string, label: string, type: string}>
     */
    public static function fields(): array
    {
        return array_map(
            fn (string $key): array => [
                'key' => $key,
                'label' => self::FIELDS[$key]['label'],
                'type' => self::FIELDS[$key]['type'],
            ],
            array_keys(self::FIELDS),
        );
    }
}
