<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Dto;

/**
 * Name and public id only, as returned by the publisher list endpoint.
 */
class Publisher
{
    private PublisherId $publisherId;
    private string $name;

    public function __construct(PublisherId $publisherId, string $name)
    {
        $this->publisherId = $publisherId;
        $this->name = $name;
    }

    public function getPublisherId(): PublisherId
    {
        return $this->publisherId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public static function buildFromResponseObject(\StdClass $obj): self
    {
        return new self(new PublisherId($obj->publisher_id), $obj->name);
    }
}
