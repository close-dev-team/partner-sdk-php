<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Dto;

class Ticket
{
    private string $scanCode;

    public function __construct(string $scanCode)
    {
        $this->scanCode = $scanCode;
    }

    public function getScanCode(): string
    {
        return $this->scanCode;
    }
}