<?php

namespace ClosePartnerSdk\Dto;

class Carousel
{
    protected string $name;
    protected string $public_id;

    /**
     * @param string $name
     * @param string $public_id
     */
    public function __construct(string $name, string $public_id)
    {
        $this->name = $name;
        $this->public_id = $public_id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public static function buildFromResponseObject(\StdClass $obj): self
    {
        return new self(
            $obj->name,
            $obj->id,
        );
    }
}