<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Endpoint;

use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\Mapper\SendMessageMapper;
use ClosePartnerSdk\HttpClient\Message\RequestBodyMediator;

final class SendMessage extends CloseEndpoint
{
    public function toAllChatsForShow(EventId $eventId, string $text): void
    {
        $this->sdk
            ->getHttpClient()
            ->post(
                $this->buildUriWithLatestVersion('/events/'.$eventId.'/messages/text'),
                [],
                RequestBodyMediator::convertStreamFromArray(
                    $this->sdk,
                    SendMessageMapper::withText($text)
                )
            );
    }
}