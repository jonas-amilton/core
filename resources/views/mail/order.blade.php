<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <title>Pedido #{{ $quote->id }}</title>
    </head>
    <body style="font-family: Arial, Helvetica, sans-serif; color: #111827; line-height: 1.5; max-width: 560px; margin: 24px auto; padding: 0 16px;">
        <h1 style="font-size: 20px; margin: 0 0 8px;">
            @if ($forAdmin)
                Novo pedido #{{ $quote->id }}
            @else
                Recebemos seu pedido #{{ $quote->id }}
            @endif
        </h1>

        <p style="margin: 0 0 16px; color: #4b5563;">
            @if ($forAdmin)
                {{ $quote->name }} enviou o pedido pelo site.
            @else
                Obrigado, {{ $quote->name }}. Vamos confirmar pagamento e entrega por e-mail.
            @endif
        </p>

        <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
            <thead>
                <tr style="text-align: left; border-bottom: 1px solid #e5e7eb;">
                    <th style="padding: 6px 0;">Item</th>
                    <th style="padding: 6px 0; text-align: center;">Qtd.</th>
                    <th style="padding: 6px 0; text-align: right;">Preço</th>
                </tr>
            </thead>
            <tbody>
                @php($total = 0.0)
                @foreach ($quote->items as $item)
                    @php($line = (float) ($item->unit_price ?? 0) * $item->quantity)
                    @php($total += $line)
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 6px 0;">{{ $item->name }}</td>
                        <td style="padding: 6px 0; text-align: center;">{{ $item->quantity }}</td>
                        <td style="padding: 6px 0; text-align: right;">
                            @if ($item->unit_price !== null)
                                R$ {{ number_format($line, 2, ',', '.') }}
                            @else
                                Sob consulta
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p style="margin: 16px 0 0; font-size: 16px; font-weight: bold; text-align: right;">
            Total: R$ {{ number_format($total, 2, ',', '.') }}
        </p>

        @if ($quote->message)
            <p style="margin: 16px 0 0; color: #4b5563;">
                <strong>Observações:</strong><br>
                {{ $quote->message }}
            </p>
        @endif

        <p style="margin: 24px 0 0; font-size: 12px; color: #9ca3af;">
            Contato do cliente: {{ $quote->email }}@if ($quote->phone) · {{ $quote->phone }}@endif
        </p>
    </body>
</html>
