<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Dto\Mapper;

use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\Mapper\CancelTicketMapper;
use ClosePartnerSdk\Dto\Mapper\ImportTicketsMapper;
use ClosePartnerSdk\Tests\Factory\Dto\TicketGroupFactory;
use DateTimeInterface;
use PHPUnit\Framework\TestCase;

class CancelTicketMapperTest extends TestCase
{
    /** @test * */
    public function provide_valid_values_in_the_request()
    {
        $eventId = new EventId('1234');
        $ticketGroup = TicketGroupFactory::createWithMultipleTickets();
        $ticket = $ticketGroup->getTickets()[0];

        $request = CancelTicketMapper::forTicketAndEvent(
            $ticketGroup,
            $ticket,
            $eventId
        );

        self::assertEquals((string)$eventId, $request['clev']);
        self::assertEquals($ticket->getScanCode(), $request['scan_code']);
        self::assertEquals($ticketGroup->getPhoneNumber(), $request['contact_phone_number']);
        self::assertEquals($ticket->getEventTime()->getStartDateTime()->format(DateTimeInterface::W3C), $request['event_start_date_time']);
    }
}
