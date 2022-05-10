<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Dto;

class Product
{
    private string $title;
    private string $description;
    private string $id;

    public function __construct(string $title) {
        $this->title = $title;
    }

    public function withId(string $productId): self
    {
        $self = clone $this;
        $self->id = $productId;

        return $self;
    }

    public function withDescription(string $description): self
    {
        $self = clone $this;
        $self->description = $description;

        return $self;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description ?? null;
    }

    public function getId(): ?string
    {
        return $this->id ?? null;
    }
}