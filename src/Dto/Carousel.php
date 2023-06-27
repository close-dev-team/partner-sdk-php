<?php

namespace ClosePartnerSdk\Dto;

class Carousel
{
    private string $name;

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public static function buildFromResponseObject(\StdClass $obj): self
    {
        return new self(
            $obj->name
        );
    }
}