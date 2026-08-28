<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Operation;

use ClosePartnerSdk\Dto\BlockContent;
use ClosePartnerSdk\Dto\BlockGroup;
use ClosePartnerSdk\Dto\BlockId;
use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\HttpClient\Message\RequestBodyMediator;

final class BlockOperation extends CloseOperation
{
    /**
     * The event's block program groups, in the order the builder shows them,
     * each with its blocks.
     *
     * Every block reports whether it can be updated and which of its fields an
     * update accepts, so this is what decides what is worth offering for
     * editing.
     *
     * @return BlockGroup[]
     * @throws \Http\Client\Exception
     * @throws \JsonException
     */
    public function getGroupsForEvent(EventId $eventId): array
    {
        $response = $this->sdk
            ->getHttpClient()
            ->get(
                $this->buildUriWithLatestVersion('/events/' . $eventId . '/groups'),
                []
            );

        $obj = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);
        $groups = [];
        foreach ($obj ?? [] as $group) {
            $groups[] = BlockGroup::buildFromResponseObject($group);
        }

        return $groups;
    }

    /**
     * Update the texts, urls and images of one block, leaving every field not
     * named untouched.
     *
     * Only blocks reporting canBeUpdated() may be updated, and only with the
     * fields they list in getEditableFields(). Every language of the event has
     * to appear in the content: a missing one is refused and nothing is
     * written.
     *
     * Messages users already received are updated afterwards, in the
     * background, so a successful call does not mean every device has caught
     * up yet.
     *
     * @throws \Http\Client\Exception
     * @throws \JsonException
     */
    public function updateBlockContent(EventId $eventId, BlockId $blockId, BlockContent $content): bool
    {
        $response = $this->sdk
            ->getHttpClient()
            ->patch(
                $this->buildUriWithLatestVersion('/events/' . $eventId . '/blocks/' . $blockId),
                [],
                RequestBodyMediator::convertStreamFromArray(
                    $this->sdk,
                    $content->toArray()
                )
            );

        $obj = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);

        return (bool)($obj->updated ?? false);
    }
}
