<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Dto\Mapper;

use ClosePartnerSdk\Dto\BubbleInfo;
use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\EventTime;
use ClosePartnerSdk\Dto\Mapper\ImportTicketsMapper;
use ClosePartnerSdk\Dto\Product;
use ClosePartnerSdk\Dto\SeatInfo;
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
            new EventTime(new DateTime($startDateTime))
        );
        $ticketGroup = new TicketGroup($phoneNumber, $ticket);

        $request = ImportTicketsMapper::forTicketGroupAndEvent(
            $ticketGroup,
            new EventId('1234')
        );

        $ticketFromRequest = $request['ticket_group']['tickets'][0];
        self::assertEquals($scanCode, $ticketFromRequest['scan_code']);
        self::assertEquals($productTitle, $ticketFromRequest['product_title']);
        self::assertEquals($startDateTime, $ticketFromRequest['event_start_date_time']);
        self::assertEquals(1, $ticketFromRequest['number_of_items']);
    }

    /** @test **/
    public function provide_ticket_with_time_slot_in_request()
    {
        $phoneNumber = '+31666111333';
        $scanCode = '1234';
        $productTitle = 'Simple product';
        $startDateTime = '2022-01-03T10:00:00+01:00';
        $timeSlot = '10:00 - 11:00';
        $ticket = new Ticket(
            $scanCode,
            new Product($productTitle),
            (new EventTime(new DateTime($startDateTime)))
                ->withTimeSlot($timeSlot)
        );
        $ticketGroup = new TicketGroup($phoneNumber, $ticket);

        $request = ImportTicketsMapper::forTicketGroupAndEvent(
            $ticketGroup,
            new EventId('1234')
        );

        $ticketFromRequest = $request['ticket_group']['tickets'][0];
        self::assertEquals($timeSlot, $ticketFromRequest['time_slot']);
    }

    /** @test **/
    public function provide_ticket_with_product_details_in_request()
    {
        $phoneNumber = '+31666111333';
        $scanCode = '1234';
        $productTitle = 'Simple product';
        $startDateTime = '2022-01-03T10:00:00+01:00';
        $productId = 'PRODUCT-1';
        $productDescription = 'This is the best product ever';
        $ticket = new Ticket(
            $scanCode,
            (new Product($productTitle))
                ->withId($productId)
                ->withDescription($productDescription),
            new EventTime(new DateTime($startDateTime))
        );
        $ticketGroup = new TicketGroup($phoneNumber, $ticket);

        $request = ImportTicketsMapper::forTicketGroupAndEvent(
            $ticketGroup,
            new EventId('1234')
        );

        $ticketFromRequest = $request['ticket_group']['tickets'][0];
        self::assertEquals($productId, $ticketFromRequest['product_id']);
        self::assertEquals($productDescription, $ticketFromRequest['product_description']);
    }

    /** @test **/
    public function provide_ticket_with_bubble_info_in_request()
    {
        $phoneNumber = '+31666111333';
        $scanCode = '1234';
        $productTitle = 'Simple product';
        $startDateTime = '2022-01-03T10:00:00+01:00';
        $bubbleName = 'bubble 1';
        $blockName = 'block 1';
        $bubbleInfo = new BubbleInfo($bubbleName);
        $bubbleInfo = $bubbleInfo->withBlock($blockName);
        $ticket = new Ticket(
            $scanCode,
            new Product($productTitle),
            new EventTime(new DateTime($startDateTime))
        );
        $ticketGroup = new TicketGroup(
            $phoneNumber,
            $ticket->withBubbleInfo(
                $bubbleInfo->withBlock($blockName)
            )
        );

        $request = ImportTicketsMapper::forTicketGroupAndEvent(
            $ticketGroup,
            new EventId('1234')
        );

        $ticketFromRequest = $request['ticket_group']['tickets'][0];
        self::assertEquals(
            [
                'bubble' => $bubbleName,
                'block' => $blockName,
            ],
            $ticketFromRequest['bubble_info']);
    }

    /** @test **/
    public function provide_ticket_with_seat_info()
    {
        $phoneNumber = '+31666111333';
        $scanCode = '1234';
        $productTitle = 'Simple product';
        $startDateTime = '2022-01-03T10:00:00+01:00';
        $row = 'Row 1';
        $chair = 'Chair 1';
        $entrance = 'E';
        $section = 'S1';
        $seatInfo = new SeatInfo;
        $ticket = new Ticket(
            $scanCode,
            new Product($productTitle),
            new EventTime(new DateTime($startDateTime))
        );
        $ticketGroup = new TicketGroup(
            $phoneNumber,
            $ticket->withSeatInfo(
                $seatInfo
                    ->withChair($chair)
                    ->withEntrance($entrance)
                    ->withRow($row)
                    ->withSection($section)
            )
        );

        $request = ImportTicketsMapper::forTicketGroupAndEvent(
            $ticketGroup,
            new EventId('1234')
        );

        $ticketFromRequest = $request['ticket_group']['tickets'][0];
        self::assertEquals(
            [
                'chair' => $chair,
                'row' => $row,
                'entrance' => $entrance,
                'section' => $section,
            ],
            $ticketFromRequest['seat_info']);
    }

    /** @test **/
    public function provide_multiple_tickets_in_request()
    {
        $phoneNumber = '+31666111333';
        $scanCode = '1234';
        $productTitle = 'Simple product';
        $startDateTime = '2022-01-03T10:00:00+01:00';
        $ticket = new Ticket(
            $scanCode,
            new Product($productTitle),
            new EventTime(new DateTime($startDateTime))
        );
        $ticketGroup = new TicketGroup(
            $phoneNumber,
            $ticket,
            $ticket,
            $ticket,
            $ticket
        );

        $request = ImportTicketsMapper::forTicketGroupAndEvent(
            $ticketGroup,
            new EventId('1234')
        );

        self::assertCount(4, $request['ticket_group']['tickets']);
    }
}
