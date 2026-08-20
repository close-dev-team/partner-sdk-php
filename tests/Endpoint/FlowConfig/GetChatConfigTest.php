<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Endpoint\FlowConfig;

use ClosePartnerSdk\Dto\ChatId;
use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\ItemFlowProperty;
use ClosePartnerSdk\Tests\Endpoint\EndpointTestCase;
use Http\Message\RequestMatcher\RequestMatcher;
use Psr\Http\Message\RequestInterface;

class GetChatConfigTest extends EndpointTestCase
{
    /** @test */
    public function call_the_chat_config_endpoint_with_both_identifiers()
    {
        $this->givenAnAuthorisedClient();
        $eventId = new EventId('CLEV1234567890');
        $chatId = new ChatId('CLCH1234567890');

        $this->mockClient
            ->on(
                new RequestMatcher('/events/' . $eventId . '/chats/' . $chatId . '/config'),
                function (RequestInterface $request) use ($eventId, $chatId) {
                    self::assertEquals('GET', $request->getMethod());
                    self::assertEquals(
                        '/api/v1/events/' . $eventId . '/chats/' . $chatId . '/config',
                        $request->getUri()->getPath()
                    );

                    return $this->mockResponse([
                        'items' => [
                            ['key' => 'welcome_message', 'value' => 'Hi there'],
                        ],
                    ]);
                }
            );

        $items = $this->givenSdk()->flowConfig()->getChatConfig($eventId, $chatId);

        self::assertCount(1, $items);
        self::assertContainsOnlyInstancesOf(ItemFlowProperty::class, $items);
        self::assertEquals('welcome_message', $items[0]->getKey());
        self::assertEquals('Hi there', $items[0]->getValue());
    }
}
