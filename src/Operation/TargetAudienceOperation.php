<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Operation;

use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\TargetAudience;
use ClosePartnerSdk\HttpClient\Message\RequestBodyMediator;

final class TargetAudienceOperation extends CloseOperation
{
    /**
     * The name has to be unique within the event.
     *
     * @throws \Http\Client\Exception
     * @throws \JsonException
     */
    public function create(EventId $eventId, TargetAudience $targetAudience): TargetAudience
    {
        $response = $this->sdk
            ->getHttpClient()
            ->post(
                $this->buildUriWithLatestVersion('/events/' . $eventId . '/target_audiences'),
                [],
                RequestBodyMediator::convertStreamFromArray(
                    $this->sdk,
                    $targetAudience->toArray()
                )
            );

        $obj = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);

        return TargetAudience::buildFromResponseObject($obj);
    }

    /**
     * The audience is addressed by its current name, which is part of the path
     * and therefore encoded: names allow spaces and run up to 255 characters.
     * The audience passed in carries the replacement name and condition, so
     * this renames as well as updates.
     *
     * @throws \Http\Client\Exception
     * @throws \JsonException
     */
    public function update(EventId $eventId, string $currentName, TargetAudience $targetAudience): TargetAudience
    {
        $response = $this->sdk
            ->getHttpClient()
            ->put(
                $this->buildUriWithLatestVersion(
                    '/events/' . $eventId . '/target_audiences/' . urlencode($currentName)
                ),
                [],
                RequestBodyMediator::convertStreamFromArray(
                    $this->sdk,
                    $targetAudience->toArray()
                )
            );

        $obj = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);

        return TargetAudience::buildFromResponseObject($obj);
    }
}
