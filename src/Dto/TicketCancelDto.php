<?php

declare(strict_types=1);

namespace ClosePartnerSdk\Dto;

class TicketCancelDto
{
    private string $scanCode;
    private string $phoneNumber;
    private EventTime $eventTime;

    public function __construct(string $scanCode, string $phoneNumber, EventTime $eventTime)
    {
        $this->scanCode = $scanCode;
        $this->phoneNumber = $phoneNumber;
        $this->eventTime = $eventTime;
    }

    /**
     * @return string
     */
    public function getScanCode(): string
    {
        return $this->scanCode;
    }

    /**
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    /**
     * @return EventTime
     */
    public function getEventTime(): EventTime
    {
        return $this->eventTime;
    }
}
