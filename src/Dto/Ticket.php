<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Dto;

use DateTimeInterface;

class Ticket
{
    private string $scanCode;
    private Product $product;
    private EventTime $eventTime;
    private ?SeatInfo $seatInfo;
    private ?BubbleInfo $bubbleInfo;
    private int $numberOfItems;

    public function __construct(
        string    $scanCode,
        Product   $product,
        EventTime $eventTime,
        int $numberOfItems = 1
    ) {
        $this->scanCode = $scanCode;
        $this->product = $product;
        $this->eventTime = $eventTime;
        $this->numberOfItems = $numberOfItems;
    }

    public function withSeatInfo(SeatInfo $seatInfo): self
    {
        $newInstance = clone $this;
        $newInstance->seatInfo = $seatInfo;

        return $newInstance;
    }

    public function withBubbleInfo(BubbleInfo $bubbleInfo): self
    {
        $newInstance = clone $this;
        $newInstance->bubbleInfo = $bubbleInfo;

        return $newInstance;
    }

    public function getScanCode(): string
    {
        return $this->scanCode;
    }

    public function getEventTime(): EventTime
    {
        return $this->eventTime;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getSeatInfo(): ?SeatInfo
    {
        return $this->seatInfo ?? null;
    }

    public function getBubbleInfo(): ?BubbleInfo
    {
        return $this->bubbleInfo ?? null;
    }

    public function getNumberOfItems(): int
    {
        return $this->numberOfItems;
    }
}