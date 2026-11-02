<?php

declare(strict_types=1);

namespace Modules\Page\Enums;

enum BlockType: string
{
    case Hero = 'hero';
    case Text = 'text';
    case Grid = 'grid';
    case Image = 'image';
    case Cta = 'cta';
    case Services = 'services';
    case Testimonials = 'testimonials';
    case Faq = 'faq';
    case Clients = 'clients';
    case Posts = 'posts';
    case Products = 'products';
    case Contact = 'contact';
    case Career = 'career';
    case Newsletter = 'newsletter';

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_map(fn (self $type): string => $type->value, self::cases());
    }
}
