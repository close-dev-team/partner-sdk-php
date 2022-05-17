<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Dto;

class ItemFlowProperty
{
    private string $key;
    private string $value;

    public function __construct(string $key, string $value) {
        $this->key = $key;
        $this->value = $value;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function toArray(): array
    {
        return [
            'key' => $this->getKey(),
            'value' => $this->getValue(),
        ];
    }
}