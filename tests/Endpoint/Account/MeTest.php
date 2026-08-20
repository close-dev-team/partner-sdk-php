<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Endpoint\Account;

use ClosePartnerSdk\Tests\Endpoint\EndpointTestCase;
use Http\Message\RequestMatcher\RequestMatcher;
use Psr\Http\Message\RequestInterface;

class MeTest extends EndpointTestCase
{
    /** @test */
    public function report_which_partner_the_credentials_belong_to()
    {
        $this->givenAnAuthorisedClient();

        $this->mockClient
            ->on(
                new RequestMatcher('/me'),
                function (RequestInterface $request) {
                    self::assertEquals('GET', $request->getMethod());
                    self::assertEquals('/api/v1/me', $request->getUri()->getPath());
                    self::assertEmpty($request->getBody()->getContents());

                    return $this->mockResponse([
                        'api_version' => 'v1',
                        'name' => 'CM.com',
                        'id' => 'client_test',
                    ]);
                }
            );

        $apiClient = $this->givenSdk()->account()->me();

        self::assertEquals('client_test', $apiClient->getId());
        self::assertEquals('CM.com', $apiClient->getName());
        self::assertEquals('v1', $apiClient->getApiVersion());
    }
}
