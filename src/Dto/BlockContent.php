<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Dto;

/**
 * New content for a block, per language.
 *
 * Every language the event has must be present: the API refuses a partial
 * translation and writes nothing, on the grounds that a half translated block
 * is worse than an unchanged one. Fields that are not named keep their current
 * value, and image fields take the public id of an already uploaded image
 * rather than its bytes.
 */
class BlockContent
{
    /** @var array<string, array<string, string|null>> */
    private array $values;

    private function __construct(array $values)
    {
        $this->values = $values;
    }

    /**
     * @param array<string, string|null> $fields field name to new value
     */
    public static function forLanguage(string $languageTag, array $fields): self
    {
        return new self([$languageTag => $fields]);
    }

    /**
     * @param array<string, string|null> $fields
     */
    public function withLanguage(string $languageTag, array $fields): self
    {
        $newInstance = clone $this;
        $newInstance->values[$languageTag] = $fields;

        return $newInstance;
    }

    /**
     * @return string[]
     */
    public function getLanguageTags(): array
    {
        return array_keys($this->values);
    }

    /**
     * @return array<string, array<string, string|null>>
     */
    public function getValues(): array
    {
        return $this->values;
    }

    public function toArray(): array
    {
        return ['values' => $this->values];
    }
}
