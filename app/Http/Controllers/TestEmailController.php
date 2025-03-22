<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;

class TestEmailController extends Controller
{
    public function testEmail()
    {
        $email = 'pasante.it@tcc.com.ec';  // Dirección a la que enviarás el correo

        Mail::raw('Este es un correo de prueba', function ($message) use ($email) {
            $message->to($email)
                    ->subject('Correo de prueba')
                    ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
        });

        return 'Correo enviado con éxito';
    }
}

