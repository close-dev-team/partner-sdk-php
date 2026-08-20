<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Dto;

use ClosePartnerSdk\Dto\WebWidgetMessage;
use PHPUnit\Framework\TestCase;

class WebWidgetMessageTest extends TestCase
{
    /** @test */
    public function a_url_widget_never_carries_html()
    {
        $message = WebWidgetMessage::withUrl('320', '480', 'https://example.org/widget');

        self::assertEquals('https://example.org/widget', $message->getUrl());
        self::assertNull($message->getHtml());
        self::assertArrayNotHasKey('html', $message->toArray());
    }

    /** @test */
    public function an_html_widget_never_carries_a_url()
    {
        $message = WebWidgetMessage::withHtml('320', '480', '<b>Hello</b>');

        self::assertEquals('<b>Hello</b>', $message->getHtml());
        self::assertNull($message->getUrl());
        self::assertArrayNotHasKey('url', $message->toArray());
    }

    /** @test */
    public function the_push_notification_message_is_left_out_until_it_is_set()
    {
        $message = WebWidgetMessage::withUrl('320', '480', 'https://example.org/widget');

        self::assertArrayNotHasKey('push_notification_message', $message->toArray());
        self::assertArrayHasKey(
            'push_notification_message',
            $message->withPushNotificationMessage('Look at this')->toArray()
        );
    }

    /** @test */
    public function adding_a_push_notification_message_leaves_the_original_alone()
    {
        $message = WebWidgetMessage::withUrl('320', '480', 'https://example.org/widget');
        $message->withPushNotificationMessage('Look at this');

        self::assertNull($message->getPushNotificationMessage());
    }
}
