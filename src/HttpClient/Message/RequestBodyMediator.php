<?php
declare(strict_types=1);

namespace ClosePartnerSdk\HttpClient\Message;

use ClosePartnerSdk\CloseSdk;
use ClosePartnerSdk\Exception\InvalidRequestJsonFormat;
use JsonException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

final class RequestBodyMediator
{
    /**
     * @throws InvalidRequestJsonFormat
     */
    public static function convertStreamFromArray(CloseSdk $sdk, array $request): StreamInterface
    {
        try {
            return $sdk->getStreamFactory()->createStream(json_encode($request, JSON_THROW_ON_ERROR));
        } catch (JsonException $exception) {
            throw new InvalidRequestJsonFormat(
                sprintf(
                    'The following request can\'t be formatted as json -> %s',
                    '[' . implode(',', $request) . ']'
                ),
                400
            );
        }
    }
}