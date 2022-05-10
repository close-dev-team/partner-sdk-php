<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Dto;

use DateTimeInterface;

class Ticket
{
    private string $scanCode;
    private Product $product;
    private DateTimeInterface $startDateTime;

    public function __construct(
        string $scanCode,
        Product $product,
        DateTimeInterface $startDateTime
    ) {
        $this->scanCode = $scanCode;
        $this->product = $product;
        $this->startDateTime = $startDateTime;
    }

    public function getScanCode(): string
    {
        return $this->scanCode;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getStartDateTime(): DateTimeInterface
    {
        return $this->startDateTime;
    }
}