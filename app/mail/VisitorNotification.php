<?php

namespace App\Mail;

use App\Models\NewVisitor;
use App\Models\Department;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VisitorNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $visitor;
    public $department;

    public function __construct(NewVisitor $visitor, Department $department)
    {
        $this->visitor = $visitor;
        $this->department = $department;
    }

    public function build()
    {
        return $this->subject('Nuevo Visitante Registrado')
                    ->view('emails.notification');
    }
}