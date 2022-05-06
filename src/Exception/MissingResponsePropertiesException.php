<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Exception;

class MissingResponsePropertiesException extends \RuntimeException implements CloseSdkException
{
    public static function forProperty(string $endpoint, string $property): self
    {
        return new self("The property '$property' is missing in the response from endpoint '$endpoint'");
    }
}