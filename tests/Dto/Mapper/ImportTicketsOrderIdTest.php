<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Dto\Mapper;

use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\Mapper\ImportTicketsMapper;
use ClosePartnerSdk\Tests\Factory\Dto\TicketGroupFactory;
use PHPUnit\Framework\TestCase;

class ImportTicketsOrderIdTest extends TestCase
{
    /** @test */
    public function provide_the_order_id_in_the_request_when_it_is_set()
    {
        $ticketGroup = TicketGroupFactory::createWithOneTicket()->withOrderId('ORDER-123');

        $request = ImportTicketsMapper::forTicketGroupAndEvent(
            $ticketGroup,
            new EventId('1234')
        );

        self::assertEquals('ORDER-123', $request['ticket_group']['order_id']);
    }

    /** @test */
    public function leave_the_order_id_out_of_the_request_when_it_is_not_set()
    {
        $request = ImportTicketsMapper::forTicketGroupAndEvent(
            TicketGroupFactory::createWithOneTicket(),
            new EventId('1234')
        );

        self::assertArrayNotHasKey('order_id', $request['ticket_group']);
    }

    /** @test */
    public function keep_the_original_group_unchanged_when_adding_an_order_id()
    {
        $ticketGroup = TicketGroupFactory::createWithOneTicket();
        $ticketGroup->withOrderId('ORDER-123');

        self::assertNull($ticketGroup->getOrderId());
    }
}
