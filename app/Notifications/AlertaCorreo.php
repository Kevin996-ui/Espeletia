<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AlertaCorreo extends Notification
{
    use Queueable;

    protected $mensaje;

    public function __construct($mensaje)
    {
        $this->mensaje = $mensaje;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Alerta Importante')
                    ->greeting('¡Hola!')
                    ->line($this->mensaje)
                    ->action('Ver detalles', url('/'))
                    ->line('Gracias por usar nuestra aplicación.');
    }

}
