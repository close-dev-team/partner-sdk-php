<?php

declare(strict_types=1);

namespace ClosePartnerSdk\Endpoint;

use ClosePartnerSdk\Auth\AuthCredentials;
use ClosePartnerSdk\Auth\Token;
use ClosePartnerSdk\CloseSdk;
use ClosePartnerSdk\HttpClient\Message\RequestBodyMediator;
use ClosePartnerSdk\HttpClient\Message\ResponseMediator;

final class Authorise extends CloseEndpoint
{
    public function withCredentials(AuthCredentials $authCredentials): Token
    {
        $httpMethodsClient = $this->sdk->getHttpClient();
        $body = RequestBodyMediator::convertStreamFromArray($this->sdk, [
            'grant_type' => 'client_credentials',
            'client_id' => $authCredentials->getClientId(),
            'client_secret' => $authCredentials->getClientSecret(),
            'scope' => '*'
        ]);
        $rawResponse = $httpMethodsClient
            ->post(
                CloseSdk::BASE_URI . '/oauth/token',
                [
                    'Content-Type' => 'application/json'
                ],
                $body
            );
        $response = ResponseMediator::getContent($rawResponse);

        return new Token(
            $response['access_token'],
            $response['expires_in']
        );
    }
}