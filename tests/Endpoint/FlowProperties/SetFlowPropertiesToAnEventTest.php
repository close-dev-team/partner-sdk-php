<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Endpoint\FlowProperties;

use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\ItemFlowProperty;
use ClosePartnerSdk\Dto\Mapper\FlowPropertiesMapper;
use ClosePartnerSdk\Tests\Endpoint\EndpointTestCase;
use Http\Message\RequestMatcher\RequestMatcher;
use Psr\Http\Message\RequestInterface;

class SetFlowPropertiesToAnEventTest extends EndpointTestCase
{
    /** @test **/
    public function call_set_properties_to_a_user_in_chat_for_event_endpoint()
    {
        $token = 'ey8393930dkdkdk';
        $this->mockClient
            ->on(
                new RequestMatcher('oauth/token'),
                fn() => $this->mockResponse([
                    "token_type" => "Bearer",
                    "expires_in" => 1113,
                    "access_token" => $token,
                ])
            );

        $eventId = new EventId('1234');

        $properties = [
            new ItemFlowProperty('key', 'value'),
            new ItemFlowProperty('key2', 'value2')
        ];

        $this->mockClient
            ->on(
                new RequestMatcher('/events/'.$eventId.'/properties'),
                function (RequestInterface $request) use ($properties) {
                self::assertEquals(
                    FlowPropertiesMapper::withProperties($properties),
                    json_decode($request->getBody()->getContents(), true),
                );
                return $this->mockResponse([]);
            });

        $this->givenSdk()->flowProperties()->setForAllUsersInAllChats(
            $eventId,
            $properties
        );
    }
}
