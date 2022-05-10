<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Dto\Mapper;

use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\Ticket;
use ClosePartnerSdk\Dto\TicketGroup;
use DateTime;
use DateTimeInterface;

class ImportTicketsMapper
{
    public static function forTicketGroupAndEvent(TicketGroup $ticketGroup, EventId $eventId): array
    {
        return [
            'clev' => $eventId,
            'ticket_group' => self::forTicketGroup($ticketGroup),
        ];
    }

    private static function forTicketGroup(TicketGroup $ticketGroup): array
    {
        return [
            'contact_phone_number' => $ticketGroup->getPhoneNumber(),
            'tickets' => array_map(static function(Ticket $ticket) {
                return self::forTicket($ticket);
            }, $ticketGroup->getTickets())
        ];
    }

    private static function forTicket(Ticket $ticket): array
    {
        return [
            'scan_code' => $ticket->getScanCode(),
            'product_title' => $ticket->getProduct()->getTitle(),
            'event_start_date_time' => $ticket->getStartDateTime()->format(DateTimeInterface::W3C)
        ];
    }
}