<?php
declare(strict_types=1);

namespace ClosePartnerSdk;

use Http\Client\Common\HttpMethodsClientInterface;
use Http\Client\Common\Plugin\BaseUriPlugin;
use Http\Client\Common\Plugin\HeaderDefaultsPlugin;

class CloseSdk
{
    private ClientBuilder $clientBuilder;

    public const LATEST_VERSION = 'v1';
    private const BASE_URI = 'https://partner.closeapi.nl';

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
                [
                    'User-Agent' => 'Close SDK',
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ]
            ])
        );
    }

    private function buildUri(Options $options): BaseUriPlugin
    {
        $uri = self::BASE_URI . "/{$options->getVersion()}";

        return new BaseUriPlugin(
            $options->getUriFactory()->createUri($uri)
        );
    }

    public function getHttpClient(): HttpMethodsClientInterface
    {
        return $this->clientBuilder->getHttpClient();
    }
}