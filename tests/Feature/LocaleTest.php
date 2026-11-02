<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class LocaleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_visitor_language_is_stored_in_the_session(): void
    {
        $this->get('/?locale=es')
            ->assertOk()
            ->assertSessionHas('locale', 'es')
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->where('locale', 'es')
            );

        $this->get('/')
            ->assertOk()
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->where('locale', 'es')
            );
    }

    public function test_unsupported_languages_are_ignored(): void
    {
        $this->get('/?locale=fr')
            ->assertOk()
            ->assertSessionMissing('locale')
            ->assertInertia(fn (Assert $inertia) => $inertia
                ->where('locale', 'pt_BR')
            );
    }
}
