<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Dto\Mapper;

use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\Mapper\CancelTicketMapper;
use ClosePartnerSdk\Tests\Factory\Dto\TicketCancelFactory;
use DateTimeInterface;
use PHPUnit\Framework\TestCase;

class CancelTicketMapperTest extends TestCase
{
    /** @test * */
    public function provide_valid_values_in_the_request()
    {
        $eventId = new EventId('1234');
        $cancelTicket = TicketCancelFactory::create();

        $request = CancelTicketMapper::forTicketAndEvent(
            $cancelTicket,
            $eventId
        );

        self::assertEquals((string)$eventId, $request['clev']);
        self::assertEquals($cancelTicket->getScanCode(), $request['scan_code']);
        self::assertEquals($cancelTicket->getPhoneNumber(), $request['contact_phone_number']);
        self::assertEquals($cancelTicket->getEventTime()->getStartDateTime()->format(DateTimeInterface::W3C), $request['event_start_date_time']);
    }
}
