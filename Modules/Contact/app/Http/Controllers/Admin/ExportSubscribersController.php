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
                    $this->csvCell($subscriber->email),
                    $subscriber->created_at?->toDateTimeString(),
                ], ';');
            }

            fclose($handle);
        }, 'inscritos.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /**
     * Force spreadsheet apps to treat formula triggers as text.
     */
    private function csvCell(string $value): string
    {
        return preg_match('/^[=+\-@\t\r]/', $value) === 1 ? "'".$value : $value;
    }
}
