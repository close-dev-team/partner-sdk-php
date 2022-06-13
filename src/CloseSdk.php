<?php
declare(strict_types=1);

namespace ClosePartnerSdk;

use ClosePartnerSdk\Dto\AuthCredentials;
use ClosePartnerSdk\Dto\Token;
use ClosePartnerSdk\Operation\Authorise;
use ClosePartnerSdk\Operation\ChatOperation;
use ClosePartnerSdk\Operation\FlowPropertyOperation;
use ClosePartnerSdk\Operation\TicketOperation;
use ClosePartnerSdk\Operation\TextMessageOperation;
use ClosePartnerSdk\Exception\Auth\InvalidCredentialsException;
use ClosePartnerSdk\HttpClient\HttpClientBuilder;
use Http\Client\Common\HttpMethodsClientInterface;
use Http\Client\Common\Plugin\BaseUriPlugin;
use Http\Client\Common\Plugin\ErrorPlugin;
use Http\Client\Common\Plugin\HeaderDefaultsPlugin;
use Http\Client\Common\Plugin\HeaderSetPlugin;
use Psr\Http\Message\StreamFactoryInterface;

class CloseSdk
{
    private HttpClientBuilder $clientBuilder;
    private AuthCredentials $authCredentials;
    private ?Token $token = null;

    /**
     * @throws Exception\ApiErrorException
     */
    public function __construct(Options $options = null) {
        $options = $options ?? new Options;
        $this->buildClientBuilder($options);
        $this->authCredentials = $options->getAuthCredentials();
    }

    /**
     * @param Options $options
     * @return void
     * @throws Exception\ApiErrorException
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

    /**
     * @throws InvalidCredentialsException
     * @throws Exception\ApiErrorException
     */
    public function ticket(): TicketOperation
    {
        if ($this->token === null) {
            $this->authoriseRequest();
        }
        return new TicketOperation($this);
    }

    /**
     * @throws InvalidCredentialsException
     * @throws Exception\ApiErrorException
     */
    public function textMessage(): TextMessageOperation
    {
        if ($this->token === null) {
            $this->authoriseRequest();
        }
        return new TextMessageOperation($this);
    }

    /**
     * @throws InvalidCredentialsException
     * @throws Exception\ApiErrorException
     */
    public function flowProperty(): FlowPropertyOperation
    {
        if ($this->token === null) {
            $this->authoriseRequest();
        }
        return new FlowPropertyOperation($this);
    }

    /**
     * @throws InvalidCredentialsException
     * @throws Exception\ApiErrorException
     */
    public function chat(): ChatOperation
    {
        if ($this->token === null) {
            $this->authoriseRequest();
        }
        return new ChatOperation($this);
    }

    public function getHttpClient(): HttpMethodsClientInterface
    {
        return $this->clientBuilder->getHttpClient();
    }

    public function getStreamFactory(): StreamFactoryInterface
    {
        return $this->clientBuilder->getStreamFactory();
    }

    /**
     * @return void
     * @throws InvalidCredentialsException
     * @throws Exception\ApiErrorException
     */
    private function authoriseRequest(): void
    {
        $this->token = $this->authorise()->withCredentials($this->authCredentials);
        $this->clientBuilder->addPlugin(
            new HeaderSetPlugin([
                'Authorization' => "Bearer $this->token",
            ])
        );
    }
}