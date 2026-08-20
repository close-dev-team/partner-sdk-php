<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Dto;

use ClosePartnerSdk\Dto\Event;
use PHPUnit\Framework\TestCase;

class EventFactoryMethodTest extends TestCase
{
    /** @test */
    public function the_misspelt_factory_method_still_builds_an_event()
    {
        $payload = (object)[
            'event_id' => 'CLEV1234567890',
            'publisher_name' => 'Close',
            'code' => 'ABCD',
            'start_date_time' => '2026-01-01T10:00:00+01:00',
            'end_date_time' => '2026-01-01T18:00:00+01:00',
            'name' => 'The musical',
            'venue' => 'Ziggo Dome',
            'chat_nickname' => 'Musical chat',
            'photo_image_url' => 'https://example.org/photo.png',
            'background_image_url' => 'https://example.org/background.png',
            'chat_message_background_color' => '#FFFFFF',
            'chat_message_text_color' => '#000000',
            'currency' => 'EUR',
            'time_zone' => 'Europe/Amsterdam',
            'locale' => 'nl_NL',
        ];

        $fromDeprecated = Event::buildFromRepsonseObject($payload);
        $fromCurrent = Event::buildFromResponseObject($payload);

        self::assertEquals($fromCurrent, $fromDeprecated);
        self::assertEquals('CLEV1234567890', (string)$fromDeprecated->getEventId());
    }
}
