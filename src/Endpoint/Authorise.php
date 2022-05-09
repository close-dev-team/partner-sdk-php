<?php

declare(strict_types=1);

namespace ClosePartnerSdk\Endpoint;

use ClosePartnerSdk\Dto\AuthCredentials;
use ClosePartnerSdk\Dto\Token;
use ClosePartnerSdk\Exception\Auth\InvalidCredentialsException;
use ClosePartnerSdk\Exception\ApiErrorException;
use ClosePartnerSdk\Exception\ConnectionException;
use ClosePartnerSdk\Exception\InvalidRequestJsonFormat;
use ClosePartnerSdk\Exception\InvalidResponseJsonFormat;
use ClosePartnerSdk\HttpClient\Message\RequestBodyMediator;
use ClosePartnerSdk\HttpClient\Message\ResponseMediator;
use ClosePartnerSdk\Validator\AuthoriseValidator;
use Http\Client\Common\Exception\ClientErrorException;

final class Authorise extends CloseEndpoint
{
    /**
     * @param AuthCredentials $authCredentials
     * @return Token
     * @throws InvalidResponseJsonFormat
     * @throws InvalidRequestJsonFormat
     * @throws ApiErrorException
     * @throws InvalidCredentialsException
     * @throws ConnectionException
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
        try {
            $rawResponse = $httpMethodsClient
                ->post(
                    '/oauth/token',
                    [
                        'Content-Type' => 'application/json'
                    ],
                    $body
                );
        } catch (ClientErrorException $exception) {
            if ($exception->getCode() === 401) {
                throw new InvalidCredentialsException(
                    'The credentials provided are not valid to connect to the Close SDK.'
                );
            }
            throw new ApiErrorException($exception->getMessage(), $exception->getCode(), $exception);
        } catch (\Exception $exception) {
            throw new ConnectionException($exception->getMessage(), $exception->getCode(), $exception);
        }

        $response = ResponseMediator::getContent($rawResponse);
        (new AuthoriseValidator('/oauth/token', $response))->validate();

        return new Token(
            $response['access_token'],
            $response['expires_in']
        );
    }
}