<?php

namespace App\Services;

use Microsoft\Graph\Generated\GraphServiceClient;

use Microsoft\Kiota\Authentication\Oauth\ClientCredentialContext;

use Microsoft\Kiota\Authentication\Oauth\ClientCredentialProvider;

use Microsoft\Kiota\Authentication\Oauth\TokenRequestContext;

use Microsoft\Kiota\Http\Guzzle\GuzzleRequestAdapter;

use Microsoft\Kiota\Serialization\Json\JsonParseNodeFactory;

use Microsoft\Kiota\Serialization\Json\JsonSerializationWriterFactory;

class MicrosoftGraphService

{

    protected $graph;

    public function __construct()

    {

        $clientId = env('MSGRAPH_CLIENT_ID');

        $clientSecret = env('MSGRAPH_CLIENT_SECRET');

        $tenantId = env('MSGRAPH_TENANT_ID');

        $scopes = ['https://graph.microsoft.com/.default'];

        $tokenRequestContext = new TokenRequestContext($scopes);

        $tokenRequestContext->clientId = $clientId;

        $tokenRequestContext->clientSecret = $clientSecret;

        $tokenRequestContext->tenantId = $tenantId;

        $authProvider = new ClientCredentialProvider($tokenRequestContext);

        $requestAdapter = new GuzzleRequestAdapter(

            $authProvider,

            new JsonSerializationWriterFactory(),

            new JsonParseNodeFactory()

        );

        $this->graph = new GraphServiceClient($requestAdapter);

    }

    public function sendMessage(string $userEmail, string $htmlContent)

    {

        $message = [

            'message' => [

                'subject' => '🔑 Notificación de Llave',

                'body' => [

                    'contentType' => 'HTML',

                    'content' => $htmlContent,

                ],

                'toRecipients' => [

                    [

                        'emailAddress' => [

                            'address' => $userEmail,

                        ],

                    ],

                ],

            ],

            'saveToSentItems' => false,

        ];

        $this->graph->users()->byUserId($userEmail)->sendMail()->post($message);

    }

}

