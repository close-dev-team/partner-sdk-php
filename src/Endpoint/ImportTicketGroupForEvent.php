<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Endpoint;

use ClosePartnerSdk\CloseSdk;
use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\TicketGroup;

class ImportTicketGroupForEvent extends CloseEndpoint
{
    public function withTicketGroupAndEventId(EventId $eventId, TicketGroup $ticketGroup): void
    {
        $this->sdk
            ->getHttpClient()
            ->post( $this->buildUriWithLatestVersion('/tickets/import'));
    }
}