<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Endpoint\FlowConfig;

use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\ItemFlowProperty;
use ClosePartnerSdk\Tests\Endpoint\EndpointTestCase;
use Http\Message\RequestMatcher\RequestMatcher;
use Psr\Http\Message\RequestInterface;

class GetConfigTest extends EndpointTestCase
{
    /** @test */
    public function call_the_event_config_endpoint()
    {
        $this->givenAnAuthorisedClient();
        $eventId = new EventId('CLEV1234567890');

        $this->mockClient
            ->on(
                new RequestMatcher('/events/' . $eventId . '/config'),
                function (RequestInterface $request) use ($eventId) {
                    self::assertEquals('GET', $request->getMethod());
                    self::assertEquals(
                        '/api/v1/events/' . $eventId . '/config',
                        $request->getUri()->getPath()
                    );

                    return $this->mockResponse([
                        'items' => [
                            ['key' => 'welcome_message', 'value' => 'Hi there'],
                            ['key' => 'language', 'value' => 'nl'],
                        ],
                    ]);
                }
            );

        $items = $this->givenSdk()->flowConfig()->getConfig($eventId);

        self::assertCount(2, $items);
        self::assertContainsOnlyInstancesOf(ItemFlowProperty::class, $items);
        self::assertEquals('welcome_message', $items[0]->getKey());
        self::assertEquals('Hi there', $items[0]->getValue());
        self::assertEquals('language', $items[1]->getKey());
        self::assertEquals('nl', $items[1]->getValue());
    }
}
