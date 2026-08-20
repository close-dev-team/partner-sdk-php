<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Dto\Survey;

/**
 * One answer a button survey offers, with how often it was picked.
 */
class SurveyOption
{
    private string $answer;
    private string $value;
    private int $count;

    public function __construct(string $answer, string $value, int $count)
    {
        $this->answer = $answer;
        $this->value = $value;
        $this->count = $count;
    }

    public function getAnswer(): string
    {
        return $this->answer;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public static function buildFromResponseObject(\StdClass $obj): self
    {
        return new self($obj->answer, $obj->value, (int)$obj->count);
    }
}
