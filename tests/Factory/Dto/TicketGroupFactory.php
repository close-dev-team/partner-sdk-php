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
        return new TicketGroup(
            '+31666111333',
            TicketFactory::withMinimalInfo()
        );
    }

    public static function createWithTimeSlot(): TicketGroup
    {
        return new TicketGroup(
            '+31666111333',
            TicketFactory::withTimeSlot()
        );
    }

    public static function createWithProductDetails(): TicketGroup
    {
        return new TicketGroup(
            '+31666111333',
            TicketFactory::withProductDetails()
        );
    }

    public static function createWithSeatInfo(): TicketGroup
    {
        return new TicketGroup(
            '+31666111333',
            TicketFactory::withSeatInfo()
        );
    }

    public static function createWithBubbleInfo(): TicketGroup
    {
        return new TicketGroup(
            '+31666111333',
            TicketFactory::withBubbleInfo()
        );
    }

    public static function createWithMultipleTickets(): TicketGroup
    {
        return new TicketGroup(
            '+31666111333',
            TicketFactory::withMinimalInfo(),
            TicketFactory::withMinimalInfo(),
            TicketFactory::withMinimalInfo(),
            TicketFactory::withMinimalInfo()
        );
    }
}