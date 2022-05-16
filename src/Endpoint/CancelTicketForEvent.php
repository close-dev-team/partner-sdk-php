<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Endpoint;

use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\Mapper\CancelTicketMapper;
use ClosePartnerSdk\Dto\Ticket;
use ClosePartnerSdk\Dto\TicketGroup;
use ClosePartnerSdk\HttpClient\Message\RequestBodyMediator;

final class CancelTicketForEvent extends CloseEndpoint
{
    public function withTicketAndEventId(EventId $eventId, TicketGroup $ticketGroup, Ticket $ticket): void
    {
        $this->sdk
            ->getHttpClient()
            ->post(
                $this->buildUriWithLatestVersion('/tickets/cancel'),
                [],
                RequestBodyMediator::convertStreamFromArray(
                    $this->sdk,
                    CancelTicketMapper::forTicketAndEvent($ticketGroup, $ticket, $eventId)
                )
            );
    }
}