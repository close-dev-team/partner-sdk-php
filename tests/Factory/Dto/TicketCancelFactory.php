<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Factory\Dto;

use ClosePartnerSdk\Dto\BubbleInfo;
use ClosePartnerSdk\Dto\EventTime;
use ClosePartnerSdk\Dto\Product;
use ClosePartnerSdk\Dto\SeatInfo;
use ClosePartnerSdk\Dto\Ticket;
use ClosePartnerSdk\Dto\TicketCancelDto;
use DateTime;

final class TicketCancelFactory
{
    public static function create(): TicketCancelDto
    {
        $scanCode = sprintf('SCAN-%s',rand(10000,110000));
        $startDateTime = '2022-01-03T10:00:00+01:00';
        $phoneNumber = '+316' . mt_rand(10000000,99999999);

        return new TicketCancelDto(
            $scanCode,
            $phoneNumber,
            self::buildEventTime($startDateTime)
        );
    }

    private static function buildEventTime(string $startDateTime): EventTime
    {
        return new EventTime(new DateTime($startDateTime));
    }
}