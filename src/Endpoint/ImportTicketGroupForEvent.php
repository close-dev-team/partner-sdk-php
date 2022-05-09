<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Endpoint;

use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\Mapper\ImportTicketsMapper;
use ClosePartnerSdk\Dto\TicketGroup;

class ImportTicketGroupForEvent extends CloseEndpoint
{
    public function withTicketGroupAndEventId(EventId $eventId, TicketGroup $ticketGroup): void
    {
        $this->sdk
            ->getHttpClient()
            ->post(
                $this->buildUriWithLatestVersion('/tickets/import'),
                [],
                ImportTicketsMapper::forTicketGroupAndEvent($ticketGroup, $eventId),
            );
    }
}