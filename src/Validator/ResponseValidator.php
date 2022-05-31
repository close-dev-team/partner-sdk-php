<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Validator;

use ClosePartnerSdk\Exception\MissingResponsePropertiesException;

abstract class ResponseValidator
{
    private string $endpoint;
    private array $response;

    public function __construct(string $endpoint, array $response)
    {
        $this->response = $response;
        $this->endpoint = $endpoint;
    }

    /**
     * @throws MissingResponsePropertiesException
     */
    abstract public function validate(): void;

    protected function requireProperty(string $propertyName): void
    {
        if (!isset($this->response[$propertyName])) {
            throw MissingResponsePropertiesException::forProperty($this->endpoint, $propertyName);
        }
    }
}