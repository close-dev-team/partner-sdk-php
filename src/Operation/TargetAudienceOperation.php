<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Operation;

use ClosePartnerSdk\Dto\Event;
use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\Mapper\CancelTicketMapper;
use ClosePartnerSdk\Dto\Mapper\ImportTicketsMapper;
use ClosePartnerSdk\Dto\Mapper\SendMessageMapper;
use ClosePartnerSdk\Dto\Ticket;
use ClosePartnerSdk\Dto\TicketCancelDto;
use ClosePartnerSdk\Dto\TicketGroup;
use ClosePartnerSdk\HttpClient\Message\RequestBodyMediator;

final class TargetAudienceOperation extends CloseOperation
{
    public function addTargetAudience(EventId $eventId, string $name, string $condition): void
    {
        $response = $this->sdk
            ->getHttpClient()
            ->post(
                uri: $this->buildUriWithLatestVersion('/events/' . $eventId . '/target_audiences'),
                headers: [],
                body: RequestBodyMediator::convertStreamFromArray(
                    $this->sdk,
                    [
                        'name' => $name,
                        'condition' => $condition,
                    ]
                )
            );

        $obj = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);
        return;
    }

    public function updateTargetAudience(EventId $eventId, string $name, string $newName, string $newCondition): void
    {
        $response = $this->sdk
            ->getHttpClient()
            ->put(
                uri: $this->buildUriWithLatestVersion('/events/' . $eventId . '/target_audiences/' . urlencode($name)),
                headers: [],
                body: RequestBodyMediator::convertStreamFromArray(
                    $this->sdk,
                    [
                        'name' => $newName,
                        'condition' => $newCondition,
                    ]
                )
            );

        $obj = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);
        return;
    }
}