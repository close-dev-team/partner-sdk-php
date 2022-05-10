<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests;

use ClosePartnerSdk\HttpClient\HttpClientBuilder;
use Http\Client\Common\HttpMethodsClientInterface;
use Http\Client\Common\Plugin\HeaderDefaultsPlugin;
use PHPUnit\Framework\TestCase;

class ClientBuilderTest extends TestCase
{
    /** @test **/
    public function resolve_http_client_that_implements_psr()
    {
        $client = (new HttpClientBuilder)->getHttpClient();
        self::assertInstanceOf(HttpMethodsClientInterface::class, $client);
    }

    /** @test **/
    public function be_able_to_add_plugins_into_the_client()
    {
        $clientBuilder = new HttpClientBuilder;
        $clientBuilder->addPlugin(new HeaderDefaultsPlugin([
            'Accept' => 'application/json',
        ]));

        $client = (new HttpClientBuilder)->getHttpClient();
        self::assertInstanceOf(HttpMethodsClientInterface::class, $client);
    }
}
