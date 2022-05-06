<?php
declare(strict_types=1);

namespace ClosePartnerSdk\HttpClient\Message;

use ClosePartnerSdk\CloseSdk;
use JsonException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

final class RequestBodyMediator
{
    /**
     * @throws JsonException
     */
    public static function convertStreamFromArray(CloseSdk $sdk, array $request): StreamInterface
    {
        return $sdk->getStreamFactory()->createStream(json_encode($request, JSON_THROW_ON_ERROR));
    }
}