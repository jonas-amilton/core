<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers\Admin;

use Inertia\Inertia;
use Inertia\Response;

final class CreateUserController
{
    public function __invoke(): Response
    {
        return Inertia::render('User::Create');
    }
}
