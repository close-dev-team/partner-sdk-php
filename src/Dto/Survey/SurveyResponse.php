<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Dto\Survey;

/**
 * What one user answered, by nickname.
 */
class SurveyResponse
{
    private string $nickname;
    private array $values;

    /**
     * @param string[] $values
     */
    public function __construct(string $nickname, array $values)
    {
        $this->nickname = $nickname;
        $this->values = $values;
    }

    public function getNickname(): string
    {
        return $this->nickname;
    }

    /**
     * @return string[]
     */
    public function getValues(): array
    {
        return $this->values;
    }

    public static function buildFromResponseObject(\StdClass $obj): self
    {
        return new self($obj->nickname, (array)($obj->values ?? []));
    }
}
