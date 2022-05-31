<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Dto\Mapper;

use ClosePartnerSdk\Dto\BubbleInfo;
use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\EventTime;
use ClosePartnerSdk\Dto\SeatInfo;
use ClosePartnerSdk\Dto\Ticket;
use ClosePartnerSdk\Dto\TicketGroup;
use DateTimeInterface;

final class ImportTicketsMapper
{
    public static function forTicketGroupAndEvent(TicketGroup $ticketGroup, EventId $eventId): array
    {
        return [
            'clev' => (string)$eventId,
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
        $properties = [
            'scan_code' => $ticket->getScanCode(),
            'number_of_items' => $ticket->getNumberOfItems(),
            'product_title' => $ticket->getProductTitle()
        ];
        $productDescription = $ticket->getProductDescription();
        $productId = $ticket->getProductId();
        $timeslot = $ticket->getTimeslot();

        $properties = array_merge($properties, self::forEventTime($ticket->getEventTime()));

        if ($timeslot !== null) {
            $properties['time_slot'] = $timeslot;
        }

        if ($productDescription !== null) {
            $properties['product_description'] = $productDescription;
        }

        if ($productId !== null) {
            $properties['product_id'] = $productId;
        }

        $bubbleInfo = $ticket->getBubbleInfo();
        if ($bubbleInfo !== null) {
            $properties = array_merge($properties, [
                'bubble_info' => self::forBubbleInfo($bubbleInfo),
            ]);
        }

        $seatInfo = $ticket->getSeatInfo();
        if ($seatInfo !== null) {
            $properties = array_merge($properties, [
                'seat_info' => self::forSeatInfo($seatInfo),
            ]);
        }

        return $properties;
    }

    private static function forEventTime(EventTime $eventTime): array
    {
        $properties = [];
        $properties['event_start_date_time'] = $eventTime->getStartDateTime()->format(DateTimeInterface::W3C);
        if ($eventTime->getTimeSlot() !== null) {
            $properties['time_slot'] = $eventTime->getTimeSlot();
        }
        return $properties;
    }

    private static function forProduct(Product $product): array
    {
        $properties = [];
        $properties['product_title'] = $product->getTitle();
        if ($product->getDescription() !== null) {
            $properties['product_description'] = $product->getDescription();
        }
        if ($product->getId() !== null) {
            $properties['product_id'] = $product->getId();
        }
        return $properties;
    }

    private static function forSeatInfo(SeatInfo $seatInfo): array
    {
        $properties = [];
        if ($seatInfo->getRow() !== null) {
            $properties['row'] = $seatInfo->getRow();
        }
        if ($seatInfo->getSection() !== null) {
            $properties['section'] = $seatInfo->getSection();
        }
        if ($seatInfo->getEntrance() !== null) {
            $properties['entrance'] = $seatInfo->getEntrance();
        }
        if ($seatInfo->getChair() !== null) {
            $properties['chair'] = $seatInfo->getChair();
        }

        return $properties;
    }

    private static function forBubbleInfo(BubbleInfo $bubbleInfo): array
    {
        $properties = [];
        if ($bubbleInfo->getBubble() !== null) {
            $properties['bubble'] = $bubbleInfo->getBubble();
        }
        if ($bubbleInfo->getBlock() !== null) {
            $properties['block'] = $bubbleInfo->getBlock();
        }

        return $properties;
    }
}