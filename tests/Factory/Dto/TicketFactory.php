<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Factory\Dto;

use ClosePartnerSdk\Dto\BubbleInfo;
use ClosePartnerSdk\Dto\EventTime;
use ClosePartnerSdk\Dto\SeatInfo;
use ClosePartnerSdk\Dto\Ticket;
use DateTime;

final class TicketFactory
{
    public static function withMinimalInfo(): Ticket
    {
        $scanCode = sprintf('SCAN-%s',rand(10000,110000));
        $productTitle = sprintf('Simple product %s', rand(10000,110000));
        $startDateTime = '2022-01-03T10:00:00+01:00';

        return new Ticket(
            $scanCode,
            self::buildEventTime($startDateTime),
            $productTitle
        );
    }

    public static function withTimeSlot(): Ticket
    {
        $scanCode = sprintf('SCAN-%s',rand(10000,110000));
        $productTitle = sprintf('Simple product %s', rand(10000,110000));
        $startDateTime = '2022-01-03T10:00:00+01:00';
        $timeslot = '11:00-12:00';

        return new Ticket(
            $scanCode,
            self::buildEventTime($startDateTime),
            $productTitle,
            1,
            $timeslot
        );
    }

    public static function withProductDetails(): Ticket
    {
        $scanCode = sprintf('SCAN-%s',rand(10000,110000));
        $productId = (string)rand(10000, 110000);
        $productTitle = sprintf('Simple product %s', $productId);
        $productDescription = sprintf('Simple description %s', $productId);
        $startDateTime = '2022-01-03T10:00:00+01:00';

        return new Ticket(
            $scanCode,
            self::buildEventTime($startDateTime),
            $productTitle,
            1,
            null,
            $productDescription,
            $productId
        );
    }

    public static function withBubbleInfo(): Ticket
    {
        $bubbleInfo = new BubbleInfo('Bubble A');
        return self::withMinimalInfo()->withBubbleInfo(
            $bubbleInfo->withBlock('Block A')
        );
    }


    public static function withSeatInfo(): Ticket
    {
        $row = 'Row 1';
        $chair = 'Chair 1';
        $entrance = 'E';
        $section = 'S1';
        $seatInfo = new SeatInfo;
        return self::withMinimalInfo()->withSeatInfo(
            $seatInfo
                ->withChair($chair)
                ->withEntrance($entrance)
                ->withRow($row)
                ->withSection($section)
        );
    }

    private static function buildEventTime(string $startDateTime): EventTime
    {
        return new EventTime(new DateTime($startDateTime));
    }
}