<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Dto;

class User
{
    private UserId $userId;
    private ?string $phoneNumber;
    private ?string $nickname;
    private array $chatIds;
    private array $emailAddresses;
    private ?string $registrationId;

    public function __construct(UserId $userId)
    {
        $this->userId = $userId;
        $this->phoneNumber = null;
        $this->nickname= null;
        $this->chatIds = [];
        $this->emailAddresses = [];
        $this->registrationId = null;
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

    /**
     * @param string[] $emailAddresses
     */
    public function withEmailAddresses(array $emailAddresses): self
    {
        $this->emailAddresses = $emailAddresses;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getEmailAddresses(): array
    {
        return $this->emailAddresses;
    }

    public function withRegistrationId(string $registrationId): self
    {
        $this->registrationId = $registrationId;
        return $this;
    }

    public function getRegistrationId(): ?string
    {
        return $this->registrationId;
    }

    /**
     * The user endpoints each return a different subset of these fields, so
     * every one beyond user_id is treated as optional.
     */
    public static function buildFromResponseObject(\StdClass $obj): self
    {
        $user = new self(new UserId($obj->user_id));

        if (!empty($obj->phone_number)) {
            $user->withPhoneNumber($obj->phone_number);
        }
        if (!empty($obj->nickname)) {
            $user->withNickname($obj->nickname);
        }
        if (!empty($obj->email_addresses)) {
            $user->withEmailAddresses((array)$obj->email_addresses);
        }
        if (!empty($obj->registration_id)) {
            $user->withRegistrationId($obj->registration_id);
        }
        foreach ($obj->chat_ids ?? [] as $chatId) {
            $user->withChatId(new ChatId($chatId));
        }

        return $user;
    }
}