<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Dto;

class TargetAudience
{
    private string $name;
    private string $condition;

    /**
     * @param string $condition An expression evaluated by the API, for example
     *                          ({chat.users} > 2) AND ("{user.deviceType}" == "IOS").
     *                          String values inside it need double quotes. The
     *                          API validates it, so it is sent through as given.
     */
    public function __construct(string $name, string $condition)
    {
        $this->name = $name;
        $this->condition = $condition;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCondition(): string
    {
        return $this->condition;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'condition' => $this->condition,
        ];
    }

    public static function buildFromResponseObject(\StdClass $obj): self
    {
        return new self($obj->name, $obj->condition);
    }
}
