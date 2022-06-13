<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Dto;

class Chat
{
    private EventId $eventId;
    private ChatId $chatId;
    private array $users;

    public function __construct(
        EventId $eventId,
        ChatId  $chatId,
    )
    {
        $this->eventId = $eventId;
        $this->chatId = $chatId;
        $this->users = [];
    }

    public function withUser(User $user): self
    {
        $this->users[] = $user;
        return $this;
    }

    public function getEventId(): EventId
    {
        return $this->eventId;
    }

    public function getChatId(): ChatId
    {
        return $this->chatId;
    }

    public function getUsers(): array
    {
        return $this->users;
    }
}