<?php

declare(strict_types=1);

namespace ClosePartnerSdk\HttpClient\Message;

use JsonException;
use Psr\Http\Message\ResponseInterface;

final class ResponseMediator
{
    /**
     * @throws JsonException
     */
    public static function getContent(ResponseInterface $response): array
    {
        return json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
    }
}