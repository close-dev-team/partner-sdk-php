<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Dto\Mapper;

use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\TicketCancelDto;
use DateTimeInterface;

final class CancelTicketMapper
{
    public static function forTicketAndEvent(TicketCancelDto $ticketCancelDto, EventId $eventId): array
    {
        // TODO: Check if the ticket is part of the received ticket group
        return [
            'clev' => (string)$eventId,
            'scan_code' => $ticketCancelDto->getScanCode(),
            'event_start_date_time' => $ticketCancelDto->getEventTime()->getStartDateTime()->format(DateTimeInterface::W3C),
            'contact_phone_number' => $ticketCancelDto->getPhoneNumber(),
        ];
    }
}