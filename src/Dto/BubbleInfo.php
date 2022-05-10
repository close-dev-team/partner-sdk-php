<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Dto;

class BubbleInfo
{
    private string $bubble;
    private string $block;

    public function __construct(string $bubble)
    {
        $this->bubble = $bubble;
    }

    public function withBlock(string $block): self
    {
        $newInstance = clone $this;
        $newInstance->block = $block;

        return $newInstance;
    }

    public function getBubble(): string
    {
        return $this->bubble;
    }

    public function getBlock(): ?string
    {
        return $this->block ?? null;
    }
}