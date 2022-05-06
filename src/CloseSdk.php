<?php
declare(strict_types=1);

namespace ClosePartnerSdk;

use ClosePartnerSdk\Endpoint\Authorise;
use Http\Client\Common\HttpMethodsClientInterface;
use Http\Client\Common\Plugin\BaseUriPlugin;
use Http\Client\Common\Plugin\HeaderDefaultsPlugin;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

class CloseSdk
{
    private ClientBuilder $clientBuilder;

    public const LATEST_VERSION = 'v1';
    public const BASE_URI = 'https://partner.closetest.nl:12443';

    public function __construct(Options $options = null) {
        $options = $options ?? new Options;
        $this->buildClientBuilder($options);
    }

    /**
     * @param Options $options
     * @return void
     */
    private function buildClientBuilder(Options $options): void
    {
        $this->clientBuilder = $options->getClientBuilder();
        $this->clientBuilder->addPlugin($this->buildUri($options));
        $this->clientBuilder->addPlugin(
            new HeaderDefaultsPlugin([
                'User-Agent' => 'Close SDK',
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
        );
    }

    private function buildUri(Options $options): BaseUriPlugin
    {
        return new BaseUriPlugin(
            $options->getUriFactory()->createUri(self::BASE_URI)
        );
    }

    public function authorise(): Authorise
    {
        return new Authorise($this);
    }

    public function getHttpClient(): HttpMethodsClientInterface
    {
        return $this->clientBuilder->getHttpClient();
    }

    public function getStreamFactory(): StreamFactoryInterface
    {
        return $this->clientBuilder->getStreamFactory();
    }

    public function getRequestFactory(): RequestFactoryInterface
    {
        return $this->clientBuilder->getRequestFactoryInterface();
    }
}