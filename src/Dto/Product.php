<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Dto;

class Product
{
    private string $title;
    private string $description;
    private string $id;

    public function __construct(
        string $title,
        string $description = null,
        string $id = null
    ) {
        $this->title = $title;
        if ($description !== null) {
            $this->description = $description;
        }
        if ($id !== null) {
            $this->id = $id;
        }
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getId(): ?string
    {
        return $this->id;
    }
}