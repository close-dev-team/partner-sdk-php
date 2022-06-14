<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Operation;

use ClosePartnerSdk\CloseSdk;
use ClosePartnerSdk\Config;

abstract class CloseOperation
{
    protected CloseSdk $sdk;

    public function __construct(CloseSdk $sdk)
    {
        $this->sdk = $sdk;
    }

    protected function buildUriWithLatestVersion(string $endpoint): string
    {
        // drop trailing slash
        if (str_starts_with($endpoint, '/')) {
            $endpoint = substr($endpoint, 1);
        }
        return sprintf('/api/%s/%s',Config::VERSION, $endpoint);
    }
}