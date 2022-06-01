<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Factory\Dto;

use ClosePartnerSdk\Dto\TicketGroup;

final class TicketGroupFactory
{
    public static function createWithoutTickets(): TicketGroup
    {
        return new TicketGroup('+31666111333');
    }

    public static function createWithOneTicket(): TicketGroup
    {
        $ticketGroup = self::createWithoutTickets();
        $ticketGroup->addTicket(TicketFactory::withMinimalInfo());

        return $ticketGroup;
    }

    public static function createWithTimeSlot(): TicketGroup
    {
        $ticketGroup = self::createWithoutTickets();
        $ticketGroup->addTicket(TicketFactory::withTimeSlot());

        return $ticketGroup;
    }

    public static function createWithProductDetails(): TicketGroup
    {
        $ticketGroup = self::createWithoutTickets();
        $ticketGroup->addTicket(TicketFactory::withProductDetails());

        return $ticketGroup;
    }

    public static function createWithSeatInfo(): TicketGroup
    {
        $ticketGroup = self::createWithoutTickets();
        $ticketGroup->addTicket(TicketFactory::withSeatInfo());

        return $ticketGroup;
    }

    public static function createWithBubbleInfo(): TicketGroup
    {
        $ticketGroup = self::createWithoutTickets();
        $ticketGroup->addTicket(TicketFactory::withBubbleInfo());

        return $ticketGroup;
    }

    public static function createWithMultipleTickets(): TicketGroup
    {
        $ticketGroup = self::createWithoutTickets();
        $ticketGroup->addTicket(TicketFactory::withMinimalInfo());
        $ticketGroup->addTicket(TicketFactory::withMinimalInfo());
        $ticketGroup->addTicket(TicketFactory::withMinimalInfo());
        $ticketGroup->addTicket(TicketFactory::withMinimalInfo());

        return $ticketGroup;
    }
}