<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Dto;

class BlockGroup
{
    private string $groupId;
    private string $name;
    private int $sortOrder;
    private array $blocks;

    /**
     * @param Block[] $blocks
     */
    public function __construct(string $groupId, string $name, int $sortOrder, array $blocks)
    {
        $this->groupId = $groupId;
        $this->name = $name;
        $this->sortOrder = $sortOrder;
        $this->blocks = $blocks;
    }

    public function getGroupId(): string
    {
        return $this->groupId;
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
     * @return Block[]
     */
    public function getBlocks(): array
    {
        return $this->blocks;
    }

    /**
     * @return Block[] only the blocks an update would be accepted for
     */
    public function getUpdatableBlocks(): array
    {
        return array_values(array_filter(
            $this->blocks,
            static fn (Block $block) => $block->canBeUpdated()
        ));
    }

    public static function buildFromResponseObject(\StdClass $obj): self
    {
        $blocks = [];
        foreach ($obj->blocks ?? [] as $block) {
            $blocks[] = Block::buildFromResponseObject($block);
        }

        return new self(
            $obj->group_id,
            $obj->name,
            (int)($obj->sort_order ?? 0),
            $blocks
        );
    }
}
