<?php

declare(strict_types=1);

namespace ClosePartnerSdk\Endpoint;

use ClosePartnerSdk\Auth\AuthCredentials;
use ClosePartnerSdk\Auth\Token;
use ClosePartnerSdk\CloseSdk;
use ClosePartnerSdk\Exception\InvalidRequestJsonFormat;
use ClosePartnerSdk\Exception\InvalidResponseJsonFormat;
use ClosePartnerSdk\HttpClient\Message\RequestBodyMediator;
use ClosePartnerSdk\HttpClient\Message\ResponseMediator;

final class Authorise extends CloseEndpoint
{
    /**
     * @param AuthCredentials $authCredentials
     * @return Token
     * @throws \Http\Client\Exception
     * @throws InvalidResponseJsonFormat
     * @throws InvalidRequestJsonFormat
     */
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
                '/oauth/token',
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