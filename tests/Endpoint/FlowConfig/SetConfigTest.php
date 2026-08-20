<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Endpoint\FlowConfig;

use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\ItemFlowProperty;
use ClosePartnerSdk\Tests\Endpoint\EndpointTestCase;
use Http\Message\RequestMatcher\RequestMatcher;
use Psr\Http\Message\RequestInterface;

class SetConfigTest extends EndpointTestCase
{
    /** @test */
    public function post_the_items_to_the_event_config_endpoint()
    {
        $this->givenAnAuthorisedClient();
        $eventId = new EventId('CLEV1234567890');

        $this->mockClient
            ->on(
                new RequestMatcher('/events/' . $eventId . '/config'),
                function (RequestInterface $request) use ($eventId) {
                    self::assertEquals('POST', $request->getMethod());
                    self::assertEquals(
                        '/api/v1/events/' . $eventId . '/config',
                        $request->getUri()->getPath()
                    );
                    self::assertEquals(
                        [
                            'items' => [
                                ['key' => 'welcome_message', 'value' => 'Hi there'],
                                ['key' => 'language', 'value' => 'nl'],
                            ],
                        ],
                        json_decode($request->getBody()->getContents(), true)
                    );

                    return $this->mockResponse([]);
                }
            );

        $this->givenSdk()->flowConfig()->setConfig($eventId, [
            new ItemFlowProperty('welcome_message', 'Hi there'),
            new ItemFlowProperty('language', 'nl'),
        ]);
    }
}
