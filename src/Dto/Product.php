<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Dto;

use DateTimeInterface;

class Product
{
    private string $scanCode;
    private string $productTitle;
    private DateTimeInterface $eventStartDateTime;
    private Price $price;
    private int $numberOfItems;
    private ?string $productId;
    private ?string $productDescription;

    public function __construct(
        string $scanCode,
        string $productTitle,
        DateTimeInterface $eventStartDateTime,
        Price $price,
        int $numberOfItems = 1,
        ?string $productId = null,
        ?string $productDescription = null
    ) {
        $this->scanCode = $scanCode;
        $this->productTitle = $productTitle;
        $this->eventStartDateTime = $eventStartDateTime;
        $this->price = $price;
        $this->numberOfItems = $numberOfItems;
        $this->productId = $productId;
        $this->productDescription = $productDescription;
    }

    public function getScanCode(): string
    {
        return $this->scanCode;
    }

    public function getProductTitle(): string
    {
        return $this->productTitle;
    }

    public function getEventStartDateTime(): DateTimeInterface
    {
        return $this->eventStartDateTime;
    }

    public function getPrice(): Price
    {
        return $this->price;
    }

    public function getNumberOfItems(): int
    {
        return $this->numberOfItems;
    }

    public function getProductId(): ?string
    {
        return $this->productId;
    }

    public function getProductDescription(): ?string
    {
        return $this->productDescription;
    }
}
