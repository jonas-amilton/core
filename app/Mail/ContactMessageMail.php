<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Modules\Contact\Models\ContactMessage;

final class ContactMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ContactMessage $message) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->message->kind === 'career'
                ? 'Novo currículo recebido'
                : 'Nova mensagem pelo site',
            replyTo: [$this->message->email],
        );
    }

    public function content(): Content
    {
        return new Content(view: 'mail.contact');
    }
}
