<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Endpoint;

use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\Mapper\ImportTicketsMapper;
use ClosePartnerSdk\Dto\TicketGroup;
use ClosePartnerSdk\HttpClient\Message\RequestBodyMediator;

final class ImportTicketGroupForEvent extends CloseEndpoint
{
    public function withTicketGroupAndEventId(EventId $eventId, TicketGroup $ticketGroup): void
    {
        $this->sdk
            ->getHttpClient()
            ->post(
                $this->buildUriWithLatestVersion('/tickets/import'),
                [],
                RequestBodyMediator::convertStreamFromArray(
                    $this->sdk,
                    ImportTicketsMapper::forTicketGroupAndEvent($ticketGroup, $eventId)
                )
            );
    }
}