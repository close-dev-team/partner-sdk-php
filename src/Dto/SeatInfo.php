<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Dto;

class SeatInfo
{
    private string $row;
    private string $chair;
    private string $section;
    private string $entrance;

    public function withRow(string $row): self
    {
        $newInstance = clone $this;
        $newInstance->row = $row;

        return $newInstance;
    }

    public function withChair(string $chair): self
    {
        $newInstance = clone $this;
        $newInstance->chair = $chair;

        return $newInstance;
    }

    public function withSection(string $section): self
    {
        $newInstance = clone $this;
        $newInstance->section = $section;

        return $newInstance;
    }

    public function withEntrance(string $entrance): self
    {
        $newInstance = clone $this;
        $newInstance->entrance = $entrance;

        return $newInstance;
    }

    public function getRow(): ?string
    {
        return $this->row ?? null;
    }

    public function getChair(): ?string
    {
        return $this->chair ?? null;
    }

    public function getSection(): ?string
    {
        return $this->section ?? null;
    }

    public function getEntrance(): ?string
    {
        return $this->entrance ?? null;
    }


}