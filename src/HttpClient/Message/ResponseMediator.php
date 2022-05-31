<?php

declare(strict_types=1);

namespace ClosePartnerSdk\HttpClient\Message;

use ClosePartnerSdk\Exception\InvalidResponseJsonFormat;
use JsonException;
use Psr\Http\Message\ResponseInterface;

final class ResponseMediator
{
    /**
     * @throws InvalidResponseJsonFormat
     */
    public static function getContent(ResponseInterface $response): array
    {
        $content = $response->getBody()->getContents();
        if (empty($content)) {
            return [];
        }
        try {
            $json = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
            if (!is_array($json)) {
                throw new InvalidResponseJsonFormat(
                    sprintf(
                        'The following response can\'t be formatted as json -> %s',
                        $content
                    ),
                    500
                );
            }

            return $json;
        } catch (JsonException $exception) {
            throw new InvalidResponseJsonFormat(
                sprintf(
                    'The following response can\'t be formatted as json -> %s',
                    $content
                ),
                500
            );
        }
    }
}