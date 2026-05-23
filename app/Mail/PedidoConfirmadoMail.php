<?php

namespace App\Mail;

use App\Models\Pedido;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PedidoConfirmadoMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly Pedido $pedido)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmacao de compra - Pedido ' . $this->pedido->referencia,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pedido-confirmado',
            with: ['pedido' => $this->pedido],
        );
    }
}
