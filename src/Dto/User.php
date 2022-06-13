<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Dto;

class User
{
    private UserId $userId;
    private ?string $phoneNumber;
    private ?string $nickname;
    private array $chatIds;

    public function __construct(UserId $userId)
    {
        $this->userId = $userId;
        $this->phoneNumber = null;
        $this->nickname= null;
        $this->chatIds = [];
    }

    public function withPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    public function withNickName(string $nickname): self
    {
        $this->nickname = $nickname;
        return $this;
    }

    public function withChatId(ChatId $chatId): self
    {
        $this->chatIds[] = $chatId;
        return $this;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }
    public function getNickname(): string
    {
        return $this->nickname;
    }

    public function getChatIds(): array
    {
        return $this->chatIds;
    }
}