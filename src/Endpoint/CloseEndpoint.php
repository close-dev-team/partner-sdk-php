<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Endpoint;

use ClosePartnerSdk\CloseSdk;

abstract class CloseEndpoint
{
    protected CloseSdk $sdk;

    public function __construct(CloseSdk $sdk)
    {
        $this->sdk = $sdk;
    }

    protected function buildUriWithLatestVersion(string $endpoint): string
    {
        return sprintf('/api/%s/%s',CloseSdk::LATEST_VERSION, $endpoint);
    }
}