<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Dto\Mapper;

use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\Ticket;
use ClosePartnerSdk\Dto\TicketGroup;
use DateTimeInterface;

final class CancelTicketMapper
{
    public static function forTicketAndEvent(TicketGroup $ticketGroup, Ticket $ticket, EventId $eventId): array
    {
        // TODO: Check if the ticket is part of the received ticket group
        return [
            'clev' => (string)$eventId,
            'scan_code' => $ticket->getScanCode(),
            'event_start_date_time' => $ticket->getEventTime()->getStartDateTime()->format(DateTimeInterface::W3C),
            'contact_phone_number' => $ticketGroup->getPhoneNumber(),
        ];
    }
}