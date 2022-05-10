<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Dto\Mapper;

use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\Mapper\ImportTicketsMapper;
use ClosePartnerSdk\Dto\Product;
use ClosePartnerSdk\Dto\Ticket;
use ClosePartnerSdk\Dto\TicketGroup;
use DateTime;
use PHPUnit\Framework\TestCase;

class ImportTicketsMapperTest extends TestCase
{
    /** @test **/
    public function provide_event_id_in_the_request()
    {
        $eventId = new EventId('1234');
        $ticketGroup = new TicketGroup('+31666111333');

        $request = ImportTicketsMapper::forTicketGroupAndEvent(
            $ticketGroup,
            $eventId
        );

        self::assertEquals((string)$eventId, $request['clev']);
    }

    /** @test **/
    public function provide_phone_number_in_request()
    {
        $phoneNumber = '+31666111333';
        $ticketGroup = new TicketGroup($phoneNumber);

        $request = ImportTicketsMapper::forTicketGroupAndEvent(
            $ticketGroup,
            new EventId('1234')
        );

        self::assertEquals($phoneNumber, $request['ticket_group']['contact_phone_number']);
    }

    /** @test **/
    public function provide_ticket_with_minimal_info_in_request()
    {
        $phoneNumber = '+31666111333';
        $scanCode = '1234';
        $productTitle = 'Simple product';
        $startDateTime = '2022-01-03T10:00:00+01:00';
        $ticket = new Ticket(
            $scanCode,
            new Product($productTitle),
            new DateTime($startDateTime)
        );
        $ticketGroup = new TicketGroup($phoneNumber, $ticket);

        $request = ImportTicketsMapper::forTicketGroupAndEvent(
            $ticketGroup,
            new EventId('1234')
        );

        $ticket = $request['ticket_group']['tickets'][0];
        self::assertEquals($scanCode, $ticket['scan_code']);
        self::assertEquals($productTitle, $ticket['product_title']);
        self::assertEquals($startDateTime, $ticket['event_start_date_time']);

    }
}
