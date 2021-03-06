<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Operation;

use ClosePartnerSdk\Dto\ChatId;
use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\ItemFlowProperty;
use ClosePartnerSdk\Dto\Mapper\FlowPropertiesMapper;
use ClosePartnerSdk\Dto\UserId;
use ClosePartnerSdk\HttpClient\Message\RequestBodyMediator;

final class FlowPropertyOperation extends CloseOperation
{
    /**
     * @param EventId $eventId
     * @param ChatId $chatId
     * @param UserId $userId
     * @param ItemFlowProperty[] $itemFlowProperties
     * @return void
     * @throws \Http\Client\Exception
     */
    public function setForOneUserInOneChat(EventId $eventId, ChatId $chatId, UserId $userId, array $itemFlowProperties): void
    {
        $this->sdk
            ->getHttpClient()
            ->post(
                $this->buildUriWithLatestVersion('/events/'.$eventId.'/chats/'.$chatId.'/users/'.$userId.'/properties'),
                [],
                RequestBodyMediator::convertStreamFromArray(
                    $this->sdk,
                    FlowPropertiesMapper::withProperties($itemFlowProperties)
                )
            );
    }

    /**
     * @param EventId $eventId
     * @param ItemFlowProperty[] $itemFlowProperties
     * @return void
     * @throws \Http\Client\Exception
     */
    public function setForAllUsersInAllChats(EventId $eventId, array $itemFlowProperties): void
    {
        $this->sdk
            ->getHttpClient()
            ->post(
                $this->buildUriWithLatestVersion('/events/'.$eventId.'/properties'),
                [],
                RequestBodyMediator::convertStreamFromArray(
                    $this->sdk,
                    FlowPropertiesMapper::withProperties($itemFlowProperties)
                )
            );
    }

    /**
     * @param EventId $eventId
     * @param UserId $userId
     * @param ItemFlowProperty[] $itemFlowProperties
     * @return void
     * @throws \Http\Client\Exception
     */
    public function setForUserInAllChats(EventId $eventId, UserId $userId, array $itemFlowProperties): void
    {
        $this->sdk
            ->getHttpClient()
            ->post(
                $this->buildUriWithLatestVersion('/events/'.$eventId.'/users/'.$userId.'/properties'),
                [],
                RequestBodyMediator::convertStreamFromArray(
                    $this->sdk,
                    FlowPropertiesMapper::withProperties($itemFlowProperties)
                )
            );
    }

    /**
     * @param EventId $eventId
     * @param UserId $userId
     * @param ChatId $chatId
     * @return void
     * @throws \Http\Client\Exception
     */
    public function getProperties(EventId $eventId, ChatId $chatId, UserId $userId): void
    {
        $this->sdk
            ->getHttpClient()
            ->get(
                $this->buildUriWithLatestVersion('/events/'.$eventId.'/chats/'.$chatId.'/users/'.$userId.'/properties'),
                []
            );
    }

    /**
     * @param EventId $eventId
     * @param UserId $userId
     * @param ChatId $chatId
     * @param string $text
     * @return string
     * @throws \Http\Client\Exception
     * @throws \JsonException
     */
    public function render(EventId $eventId, ChatId $chatId, UserId $userId, string $text): string
    {
        $response = $this->sdk
            ->getHttpClient()
            ->post(
                $this->buildUriWithLatestVersion('/events/'.$eventId.'/chats/'.$chatId.'/users/'.$userId.'/properties/render'),
                [],
                RequestBodyMediator::convertStreamFromArray(
                    $this->sdk,
                    FlowPropertiesMapper::render($text)
                )
            );

        $obj = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);
        if (!isset($obj) && !isset($obj->text) && empty($obj->text)) {
            return "";
        }

        return $obj->text;
    }
}