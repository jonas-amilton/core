<?php

declare(strict_types=1);

namespace Modules\Contact\Http\Controllers\Admin;

use Modules\Contact\Models\Subscriber;
use Modules\Contact\Services\ContactService;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class ExportSubscribersController
{
    public function __invoke(ContactService $contact): StreamedResponse
    {
        $subscribers = $contact->subscribers();

        return response()->streamDownload(function () use ($subscribers): void {
            $handle = fopen('php://output', 'w');

            if ($handle === false) {
                return;
            }

            fputcsv($handle, ['email', 'inscrito_em'], ';');

            foreach ($subscribers as $subscriber) {
                /** @var Subscriber $subscriber */
                fputcsv($handle, [
                    $subscriber->email,
                    $subscriber->created_at?->toDateTimeString(),
                ], ';');
            }

            fclose($handle);
        }, 'inscritos.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
