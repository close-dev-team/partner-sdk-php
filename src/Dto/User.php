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

    static public function buildFromRepsonseObject(\StdClass $obj): self
    {
        $user = new User(new UserId($obj->user_id));

        if (!is_null(@$obj->phone_number)) {
            $user = $user->withPhoneNumber($obj->phone_number);
        }
        if (!is_null(@$obj->nickname)) {
            $user = $user->withNickName($obj->nickname);
        }
        if (!is_null(@$obj->chat_ids)) {
            $user = $user->withChatIds($obj->chat_ids);
        }
        return $user;
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

    public function withChatIds(array $chatIds): self
    {
        $this->chatIds = [];
        foreach ($chatIds as $chatId) {
            if (is_string($chatId)) {
                $this->chatIds[] = new ChatId($chatId);
            } else {
                $this->chatIds[] = $chatId;
            }
        }
        return $this;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }
    
    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function getChatIds(): array
    {
        return $this->chatIds;
    }
}