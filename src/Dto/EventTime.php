<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Dto;

use DateTimeInterface;

class EventTime
{
    private DateTimeInterface $startDateTime;
    private ?string $timeSlot;

    public function __construct(DateTimeInterface $startDateTime)
    {
        $this->startDateTime = $startDateTime;
    }

    public function withTimeSlot(string $timeSlot): self
    {
        $newInstance = clone $this;
        $newInstance->timeSlot = $timeSlot;

        return $newInstance;
    }

    /**
     * @return DateTimeInterface
     */
    public function getStartDateTime(): DateTimeInterface
    {
        return $this->startDateTime;
    }

    public function getTimeSlot(): ?string
    {
        return $this->timeSlot ?? null;
    }
}