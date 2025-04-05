<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class TeamsNotificationService
{
    protected $webhookUrl;

    public function __construct($webhookUrl = null)
    {
        //$this->webhookUrl = $webhookUrl ?? 'https://tabacarcenuio.webhook.office.com/webhookb2/937545b1-8cfd-4af2-834e-3d8baee7990f@aa4c7ba9-18cc-495c-adf8-72c96ccab147/IncomingWebhook/e0b0d8663bcf4ab6bbcaf8a44fd97f56/7e551e85-76f7-484e-9c6a-e194df5b5a34/V2r4trcFZxKXx5-7v3kclJ6xRzR7lJ0zxLMCbwf6ltyqk1';
    }

    /**
     * Enviar un mensaje a Microsoft Teams
     *
     * @param string $message
     * @return mixed
     */
    public function sendMessage($message)
    {
        $client = new Client();

        try {

            $response = $client->post($this->webhookUrl, [
                'json' => [
                    'text' => $message
                ]
            ]);

            if ($response->getStatusCode() === 200) {
                return 'Mensaje enviado con éxito';
            } else {
                return 'Error al enviar el mensaje. Código: ' . $response->getStatusCode();
            }

        } catch (RequestException $e) {

            return 'Hubo un error al enviar el mensaje: ' . $e->getMessage();
        }
    }
}
