<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <title>Mensagem pelo site</title>
    </head>
    <body style="font-family: Arial, Helvetica, sans-serif; color: #111827; line-height: 1.5; max-width: 560px; margin: 24px auto; padding: 0 16px;">
        <h1 style="font-size: 20px; margin: 0 0 8px;">
            {{ $message->kind === 'career' ? 'Novo currículo recebido' : 'Nova mensagem pelo site' }}
        </h1>

        <p style="margin: 0 0 16px; color: #4b5563;">
            <strong>{{ $message->name }}</strong><br>
            {{ $message->email }}@if ($message->phone) · {{ $message->phone }}@endif
        </p>

        <p style="margin: 0; white-space: pre-line;">{{ $message->message }}</p>
    </body>
</html>
