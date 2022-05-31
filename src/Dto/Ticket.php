<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Dto;

class Ticket
{
    private string $scanCode;
    private string $productTitle;
    private EventTime $eventTime;
    private int $numberOfItems;
    private ?SeatInfo $seatInfo;
    private ?BubbleInfo $bubbleInfo;
    private ?string $timeslot;
    private ?string $productDescription;
    private ?string $productId;

    public function __construct(
        string    $scanCode,
        EventTime $eventTime,
        string $productTitle,
        int $numberOfItems = 1,
        string $timeslot = null,
        string $productDescription = null,
        string $productId = null
    ) {
        $this->scanCode = $scanCode;
        $this->eventTime = $eventTime;
        $this->productTitle = $productTitle;
        $this->numberOfItems = $numberOfItems;
        $this->timeslot = $timeslot;
        $this->productDescription = $productDescription;
        $this->productId = $productId;
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

    public function getProductTitle(): string
    {
        return $this->productTitle;
    }

    public function getProductDescription(): ?string
    {
        return $this->productDescription;
    }

    public function getTimeslot(): ?string
    {
        return $this->timeslot;
    }

    public function getProductId(): ?string
    {
        return $this->productId;
    }
}