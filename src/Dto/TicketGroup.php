<?php
declare(strict_types=1);


namespace ClosePartnerSdk\Dto;


class TicketGroup
{
    private string $phoneNumber;
    private array $tickets;
    private ?string $orderId = null;

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

    public function addTicket(Ticket $ticket): void
    {
        $this->tickets[] = $ticket;
    }

    public function withOrderId(string $orderId): self
    {
        $newInstance = clone $this;
        $newInstance->orderId = $orderId;

        return $newInstance;
    }

    public function getOrderId(): ?string
    {
        return $this->orderId;
    }
}