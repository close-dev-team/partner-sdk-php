<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Operation;

use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\Mapper\CancelTicketMapper;
use ClosePartnerSdk\Dto\Mapper\ImportTicketsMapper;
use ClosePartnerSdk\Dto\Ticket;
use ClosePartnerSdk\Dto\TicketCancelDto;
use ClosePartnerSdk\Dto\TicketGroup;
use ClosePartnerSdk\HttpClient\Message\RequestBodyMediator;

final class TicketOperation extends CloseOperation
{
    public function import(EventId $eventId, TicketGroup $ticketGroup): void
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

    public function cancel(EventId $eventId, TicketCancelDto $ticketCancelDto): void
    {
        $this->sdk
            ->getHttpClient()
            ->post(
                $this->buildUriWithLatestVersion('/tickets/cancel'),
                [],
                RequestBodyMediator::convertStreamFromArray(
                    $this->sdk,
                    CancelTicketMapper::forTicketAndEvent($ticketCancelDto, $eventId)
                )
            );
    }
}