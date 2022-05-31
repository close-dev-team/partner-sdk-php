<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Dto\Mapper;

use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\Mapper\ImportTicketsMapper;
use ClosePartnerSdk\Tests\Factory\Dto\TicketGroupFactory;
use DateTime;
use PHPUnit\Framework\TestCase;

class ImportTicketsMapperTest extends TestCase
{
    /** @test * */
    public function provide_event_id_in_the_request()
    {
        $eventId = new EventId('1234');
        $ticketGroup = TicketGroupFactory::createWithoutTickets();

        $request = ImportTicketsMapper::forTicketGroupAndEvent(
            $ticketGroup,
            $eventId
        );

        self::assertEquals((string)$eventId, $request['clev']);
    }

    /** @test * */
    public function provide_phone_number_in_request()
    {
        $ticketGroup = TicketGroupFactory::createWithoutTickets();

        $request = ImportTicketsMapper::forTicketGroupAndEvent(
            $ticketGroup,
            new EventId('1234')
        );

        self::assertEquals($ticketGroup->getPhoneNumber(), $request['ticket_group']['contact_phone_number']);
    }

    /** @test * */
    public function provide_ticket_with_minimal_info_in_request()
    {
        $ticketGroup = TicketGroupFactory::createWithOneTicket();
        $ticket = $ticketGroup->getTickets()[0];

        $request = ImportTicketsMapper::forTicketGroupAndEvent(
            $ticketGroup,
            new EventId('1234')
        );

        $ticketFromRequest = $request['ticket_group']['tickets'][0];
        self::assertEquals($ticket->getScanCode(), $ticketFromRequest['scan_code']);
        self::assertEquals(
            $ticket->getProduct()->getTitle(),
            $ticketFromRequest['product_title']
        );
        self::assertEquals(
            $ticket
                ->getEventTime()
                ->getStartDateTime()
                ->format(DateTime::W3C),
            $ticketFromRequest['event_start_date_time']
        );
        self::assertEquals(1, $ticketFromRequest['number_of_items']);
    }

    /** @test * */
    public function provide_ticket_with_time_slot_in_request()
    {
        $ticketGroup = TicketGroupFactory::createWithTimeSlot();
        $ticket = $ticketGroup->getTickets()[0];

        $request = ImportTicketsMapper::forTicketGroupAndEvent(
            $ticketGroup,
            new EventId('1234')
        );

        $ticketFromRequest = $request['ticket_group']['tickets'][0];
        self::assertEquals(
            $ticket->getEventTime()->getTimeSlot(),
            $ticketFromRequest['time_slot']
        );
    }

    /** @test * */
    public function provide_ticket_with_product_details_in_request()
    {
        $ticketGroup = TicketGroupFactory::createWithProductDetails();
        $ticket = $ticketGroup->getTickets()[0];

        $request = ImportTicketsMapper::forTicketGroupAndEvent(
            $ticketGroup,
            new EventId('1234')
        );

        $ticketFromRequest = $request['ticket_group']['tickets'][0];
        self::assertEquals($ticket->getProduct()->getId(), $ticketFromRequest['product_id']);
        self::assertEquals($ticket->getProduct()->getDescription(), $ticketFromRequest['product_description']);
    }

    /** @test * */
    public function provide_ticket_with_bubble_info_in_request()
    {
        $ticketGroup = TicketGroupFactory::createWithBubbleInfo();
        $ticket = $ticketGroup->getTickets()[0];

        $request = ImportTicketsMapper::forTicketGroupAndEvent(
            $ticketGroup,
            new EventId('1234')
        );

        $ticketFromRequest = $request['ticket_group']['tickets'][0];
        self::assertEquals(
            [
                'bubble' => $ticket->getBubbleInfo()->getBubble(),
                'block' => $ticket->getBubbleInfo()->getBlock(),
            ],
            $ticketFromRequest['bubble_info']);
    }

    /** @test * */
    public function provide_ticket_with_seat_info()
    {
        $ticketGroup = TicketGroupFactory::createWithSeatInfo();

        $request = ImportTicketsMapper::forTicketGroupAndEvent(
            $ticketGroup,
            new EventId('1234')
        );

        $ticketFromRequest = $request['ticket_group']['tickets'][0];
        $seatInfo = $ticketGroup->getTickets()[0]->getSeatInfo();
        self::assertEquals(
            [
                'chair' => $seatInfo->getChair(),
                'row' => $seatInfo->getRow(),
                'entrance' => $seatInfo->getEntrance(),
                'section' => $seatInfo->getSection(),
            ],
            $ticketFromRequest['seat_info']);
    }

    /** @test * */
    public function provide_multiple_tickets_in_request()
    {
        $ticketGroup = TicketGroupFactory::createWithMultipleTickets();

        $request = ImportTicketsMapper::forTicketGroupAndEvent(
            $ticketGroup,
            new EventId('1234')
        );

        self::assertCount(
            count($ticketGroup->getTickets()), $request['ticket_group']['tickets']
        );
    }
}
