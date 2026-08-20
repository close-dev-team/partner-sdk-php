<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Dto;

/**
 * Push notification credentials for a publisher.
 *
 * Apple needs its three fields together to be usable, so they are set as a
 * group rather than one at a time.
 */
class PushInfo
{
    private ?string $googleServiceEncoded;
    private ?string $appleKeyId;
    private ?string $appleTeamId;
    private ?string $appleKeyEncoded;

    private function __construct()
    {
        $this->googleServiceEncoded = null;
        $this->appleKeyId = null;
        $this->appleTeamId = null;
        $this->appleKeyEncoded = null;
    }

    public static function forGoogle(string $googleServiceEncoded): self
    {
        $instance = new self();
        $instance->googleServiceEncoded = $googleServiceEncoded;

        return $instance;
    }

    public static function forApple(string $keyId, string $teamId, string $keyEncoded): self
    {
        $instance = new self();
        $instance->appleKeyId = $keyId;
        $instance->appleTeamId = $teamId;
        $instance->appleKeyEncoded = $keyEncoded;

        return $instance;
    }

    public function withGoogle(string $googleServiceEncoded): self
    {
        $newInstance = clone $this;
        $newInstance->googleServiceEncoded = $googleServiceEncoded;

        return $newInstance;
    }

    public function withApple(string $keyId, string $teamId, string $keyEncoded): self
    {
        $newInstance = clone $this;
        $newInstance->appleKeyId = $keyId;
        $newInstance->appleTeamId = $teamId;
        $newInstance->appleKeyEncoded = $keyEncoded;

        return $newInstance;
    }

    public function getGoogleServiceEncoded(): ?string
    {
        return $this->googleServiceEncoded;
    }

    public function getAppleKeyId(): ?string
    {
        return $this->appleKeyId;
    }

    public function getAppleTeamId(): ?string
    {
        return $this->appleTeamId;
    }

    public function getAppleKeyEncoded(): ?string
    {
        return $this->appleKeyEncoded;
    }

    public function toArray(): array
    {
        $properties = [];

        if ($this->googleServiceEncoded !== null) {
            $properties['google_service_encoded'] = $this->googleServiceEncoded;
        }
        if ($this->appleKeyId !== null) {
            $properties['apple_key_id'] = $this->appleKeyId;
            $properties['apple_team_id'] = $this->appleTeamId;
            $properties['apple_key_encoded'] = $this->appleKeyEncoded;
        }

        return $properties;
    }
}
