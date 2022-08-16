<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Operation;

use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\ItemFlowProperty;
use ClosePartnerSdk\Dto\Mapper\FlowPropertiesMapper;
use ClosePartnerSdk\HttpClient\Message\RequestBodyMediator;

final class FlowConfigOperation extends CloseOperation
{
    /**
     * @param EventId $eventId
     * @return array
     * @throws \Http\Client\Exception
     */
    public function getConfig(EventId $eventId): array
    {
        $response = $this->sdk
            ->getHttpClient()
            ->get(
                $this->buildUriWithLatestVersion('/events/' . $eventId . '/config'),
                []
            );

        $obj = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);
        $items = [];
        foreach ($obj->items as $item) {
            $items[] = new ItemFlowProperty($item->key, $item->value);
        }

        return $items;
    }

    /**
     * @param EventId $eventId
     * @param ItemFlowProperty[] $itemFlowProperties
     * @return void
     * @throws \Http\Client\Exception
     */
    public function setConfig(EventId $eventId, array $itemFlowProperties): void
    {
        $this->sdk
            ->getHttpClient()
            ->post(
                $this->buildUriWithLatestVersion('/events/'.$eventId.'/config'),
                [],
                RequestBodyMediator::convertStreamFromArray(
                    $this->sdk,
                    FlowPropertiesMapper::withProperties($itemFlowProperties)
                )
            );
    }

    /**
     * @param EventId $eventId
     * @param ChatId $chatId
     * @return array
     * @throws \Http\Client\Exception
     */
    public function getChatConfig(EventId $eventId): array
    {
        $response = $this->sdk
            ->getHttpClient()
            ->get(
                $this->buildUriWithLatestVersion('/events/' . $eventId . '/chats/' . $chatId . '/config'),
                []
            );

        $obj = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);
        $items = [];
        foreach ($obj->items as $item) {
            $items[] = new ItemFlowProperty($item->key, $item->value);
        }

        return $items;
    }

    /**
     * @param EventId $eventId
     * @param ChatId $chatId
     * @param ItemFlowProperty[] $itemFlowProperties
     * @return void
     * @throws \Http\Client\Exception
     */
    public function setChatConfig(EventId $eventId, ChatId $chatId, array $itemFlowProperties): void
    {
        $this->sdk
            ->getHttpClient()
            ->post(
                $this->buildUriWithLatestVersion('/events/' . $eventId . '/chats/' . $chatId . '/config'),
                [],
                RequestBodyMediator::convertStreamFromArray(
                    $this->sdk,
                    FlowPropertiesMapper::withProperties($itemFlowProperties)
                )
            );
    }
}
