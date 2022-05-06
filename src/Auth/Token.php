<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Auth;

class Token
{
    private string $accessToken;
    private int $expirationInSeconds;

    public function __construct(string $accessToken, int $expirationInSeconds)
    {
        $this->accessToken = $accessToken;
        $this->expirationInSeconds = $expirationInSeconds;
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getExpirationInSeconds(): int
    {
        return $this->expirationInSeconds;
    }

    public function __toString()
    {
        return $this->accessToken;
    }
}