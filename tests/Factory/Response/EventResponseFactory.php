<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Factory\Response;

final class EventResponseFactory
{
    /**
     * The payload shape returned by the event endpoints.
     *
     * @param array $overrides
     * @return array
     */
    public static function create(array $overrides = []): array
    {
        return array_merge([
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
            'admin_user_ids' => ['CLUS1111111111', 'CLUS2222222222'],
        ], $overrides);
    }
}
