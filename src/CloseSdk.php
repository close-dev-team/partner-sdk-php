<?php
declare(strict_types=1);

namespace ClosePartnerSdk;

use ClosePartnerSdk\Endpoint\Authorise;
use ClosePartnerSdk\HttpClient\HttpClientFactory;
use Http\Client\Common\HttpMethodsClientInterface;
use Http\Client\Common\Plugin\BaseUriPlugin;
use Http\Client\Common\Plugin\ErrorPlugin;
use Http\Client\Common\Plugin\HeaderDefaultsPlugin;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

class CloseSdk
{
    private HttpClientFactory $clientBuilder;

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
        $this->clientBuilder->addPlugin(new ErrorPlugin());
    }

    private function buildUri(Options $options): BaseUriPlugin
    {
        return new BaseUriPlugin(
            $options->getUriFactory()->createUri(Config::BASE_URI)
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
}