<?php
declare(strict_types=1);


namespace ClosePartnerSdk\Dto;


class TicketGroup
{
    private string $phoneNumber;
    private array $tickets;

    public function __construct(string $phoneNumber, Ticket ...$tickets)
    {
        $this->phoneNumber = $phoneNumber;
        $this->tickets = $tickets;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function getTickets(): array
    {
        return $this->tickets;
    }
}