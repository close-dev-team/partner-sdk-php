<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Dto;

/**
 * One block of an event's program, as the builder shows it.
 */
class Block
{
    private BlockId $blockId;
    private string $type;
    private string $name;
    private int $sortOrder;
    private bool $canBeUpdated;
    private array $editableFields;
    private array $values;

    /**
     * @param string[] $editableFields
     * @param array<string, array<string, string|null>> $values language tag to field name to value
     */
    public function __construct(
        BlockId $blockId,
        string $type,
        string $name,
        int $sortOrder,
        bool $canBeUpdated,
        array $editableFields,
        array $values
    ) {
        $this->blockId = $blockId;
        $this->type = $type;
        $this->name = $name;
        $this->sortOrder = $sortOrder;
        $this->canBeUpdated = $canBeUpdated;
        $this->editableFields = $editableFields;
        $this->values = $values;
    }

    public function getBlockId(): BlockId
    {
        return $this->blockId;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSortOrder(): int
    {
        return $this->sortOrder;
    }

    /**
     * An update of a block reporting false is refused, so this decides what
     * is worth offering for editing.
     */
    public function canBeUpdated(): bool
    {
        return $this->canBeUpdated;
    }

    /**
     * @return string[] the only field names an update of this block accepts
     */
    public function getEditableFields(): array
    {
        return $this->editableFields;
    }

    /**
     * @return array<string, array<string, string|null>> language tag to field name to value
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * @return string[] every language tag this block holds content for
     */
    public function getLanguageTags(): array
    {
        return array_keys($this->values);
    }

    public function getValue(string $languageTag, string $field): ?string
    {
        return $this->values[$languageTag][$field] ?? null;
    }

    public static function buildFromResponseObject(\StdClass $obj): self
    {
        $values = [];
        // values is an object keyed by language tag, but encodes as [] when the
        // block holds nothing editable.
        foreach ((array)($obj->values ?? []) as $languageTag => $fields) {
            $values[$languageTag] = (array)$fields;
        }

        return new self(
            new BlockId($obj->block_id),
            $obj->type,
            $obj->name,
            (int)($obj->sort_order ?? 0),
            (bool)($obj->can_be_updated ?? false),
            (array)($obj->editable_fields ?? []),
            $values
        );
    }
}
