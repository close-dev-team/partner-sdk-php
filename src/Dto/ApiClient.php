<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Dto;

/**
 * The partner credentials the SDK is authenticated as.
 */
class ApiClient
{
    private string $id;
    private string $name;
    private string $apiVersion;

    public function __construct(string $id, string $name, string $apiVersion)
    {
        $this->id = $id;
        $this->name = $name;
        $this->apiVersion = $apiVersion;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getApiVersion(): string
    {
        return $this->apiVersion;
    }

    public static function buildFromResponseObject(\StdClass $obj): self
    {
        return new self($obj->id, $obj->name, $obj->api_version);
    }
}
