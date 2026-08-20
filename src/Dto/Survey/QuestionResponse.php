<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Dto\Survey;

/**
 * One user's answer to a single question.
 */
class QuestionResponse
{
    private string $nickname;

    /** @var mixed a number for a slider survey, a string for a text survey */
    private $value;

    /**
     * @param mixed $value
     */
    public function __construct(string $nickname, $value)
    {
        $this->nickname = $nickname;
        $this->value = $value;
    }

    public function getNickname(): string
    {
        return $this->nickname;
    }

    /**
     * @return mixed a number for a slider survey, a string for a text survey
     */
    public function getValue()
    {
        return $this->value;
    }

    public static function buildFromResponseObject(\StdClass $obj): self
    {
        return new self($obj->nickname, $obj->value ?? null);
    }
}
