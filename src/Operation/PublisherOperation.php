<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Operation;

use ClosePartnerSdk\Dto\ItemFlowProperty;
use ClosePartnerSdk\Dto\Mapper\FlowPropertiesMapper;
use ClosePartnerSdk\Dto\PublisherId;
use ClosePartnerSdk\Dto\PushInfo;
use ClosePartnerSdk\Dto\UserId;
use ClosePartnerSdk\HttpClient\Message\RequestBodyMediator;

final class PublisherOperation extends CloseOperation
{
    /**
     * @throws \Http\Client\Exception
     */
    public function setPushInfo(PublisherId $publisherId, PushInfo $pushInfo): void
    {
        $this->sdk
            ->getHttpClient()
            ->post(
                $this->buildUriWithLatestVersion('/publishers/' . $publisherId . '/push_info'),
                [],
                RequestBodyMediator::convertStreamFromArray(
                    $this->sdk,
                    $pushInfo->toArray()
                )
            );
    }

    /**
     * @throws \Http\Client\Exception
     */
    public function deletePushInfo(PublisherId $publisherId): void
    {
        $this->sdk
            ->getHttpClient()
            ->delete(
                $this->buildUriWithLatestVersion('/publishers/' . $publisherId . '/push_info'),
                []
            );
    }

    /**
     * Unlike the event properties endpoint, this one answers with a bare
     * list rather than an items envelope.
     *
     * @return ItemFlowProperty[]
     * @throws \Http\Client\Exception
     * @throws \JsonException
     */
    public function getProperties(PublisherId $publisherId, UserId $userId): array
    {
        $response = $this->sdk
            ->getHttpClient()
            ->get(
                $this->buildUriWithLatestVersion(
                    '/publishers/' . $publisherId . '/users/' . $userId . '/properties'
                ),
                []
            );

        $obj = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);
        $items = [];
        foreach ($obj ?? [] as $item) {
            $items[] = new ItemFlowProperty($item->key, $item->value);
        }

        return $items;
    }

    /**
     * @param ItemFlowProperty[] $itemFlowProperties
     * @throws \Http\Client\Exception
     */
    public function setProperties(PublisherId $publisherId, UserId $userId, array $itemFlowProperties): void
    {
        $this->sdk
            ->getHttpClient()
            ->post(
                $this->buildUriWithLatestVersion(
                    '/publishers/' . $publisherId . '/users/' . $userId . '/properties'
                ),
                [],
                RequestBodyMediator::convertStreamFromArray(
                    $this->sdk,
                    FlowPropertiesMapper::withProperties($itemFlowProperties)
                )
            );
    }
}
