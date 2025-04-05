<?php

namespace App\Mail;

use App\Models\KeyLog;
use App\Models\KeyType;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class KeyLogNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $keyLog;
    public $keyType;

    public function __construct(KeyLog $keyLog, KeyType $keyType)
    {
        $this->keyLog = $keyLog;
        $this->keyType = $keyType;
    }

    public function build()
    {
        return $this->subject('Nueva Llave Registrada')
                    ->view('emails.keylog_notification');
    }
}