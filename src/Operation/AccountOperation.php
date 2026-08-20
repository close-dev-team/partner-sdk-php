<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Operation;

use ClosePartnerSdk\Dto\ApiClient;

final class AccountOperation extends CloseOperation
{
    /**
     * Which partner the current credentials belong to, and the API version
     * answering. Useful as a connectivity and credentials check.
     *
     * @throws \Http\Client\Exception
     * @throws \JsonException
     */
    public function me(): ApiClient
    {
        $response = $this->sdk
            ->getHttpClient()
            ->get($this->buildUriWithLatestVersion('/me'), []);

        $obj = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);

        return ApiClient::buildFromResponseObject($obj);
    }
}
