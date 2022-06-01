<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Endpoint\FlowProperties;

use ClosePartnerSdk\Dto\ChatId;
use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\UserId;
use ClosePartnerSdk\Tests\Endpoint\EndpointTestCase;
use Http\Message\RequestMatcher\RequestMatcher;
use Psr\Http\Message\RequestInterface;

class GetFlowPropertiesFromAUserInAChatForAnEventTest extends EndpointTestCase
{
    /** @test **/
    public function call_get_properties_from_a_user_in_chat_for_event_endpoint()
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
        $userId = new UserId('4567');
        $chatId = new ChatId('8901');

        $this->mockClient
            ->on(
                new RequestMatcher('/events/'.$eventId.'/chats/'.$chatId.'/users/'.$userId.'/properties'),
                function (RequestInterface $request) {
                self::assertEmpty($request->getBody()->getContents());
                return $this->mockResponse([]);
            });

        $this->givenSdk()->flowProperty()->getProperties(
            $eventId,
            $chatId,
            $userId
        );
    }
}
