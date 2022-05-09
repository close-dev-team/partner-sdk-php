<?php
declare(strict_types=1);

namespace ClosePartnerSdk;

use ClosePartnerSdk\Dto\AuthCredentials;
use ClosePartnerSdk\HttpClient\HttpClientFactory;
use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Message\UriFactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class Options
{
    private array $options;

    public function __construct(array $options = [])
    {
        $resolver = new OptionsResolver;
        $this->configureOptions($resolver);

        $this->options = $resolver->resolve($options);
    }

    private function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(['client_id', 'client_secret']);
        $resolver->setDefaults(
            [
                'client_builder' => new HttpClientFactory,
                'uri_factory' => Psr17FactoryDiscovery::findUriFactory(),
            ]
        );

        $resolver->setAllowedTypes('client_builder', HttpClientFactory::class);
        $resolver->setAllowedTypes('uri_factory', UriFactoryInterface::class);
    }

    public function getClientBuilder(): HttpClientFactory
    {
        return $this->options['client_builder'];
    }

    public function getUriFactory(): UriFactoryInterface
    {
        return $this->options['uri_factory'];
    }

    public function getVersion(): string
    {
        return $this->options['version'];
    }

    public function getAuthCredentials(): AuthCredentials
    {
        return new AuthCredentials($this->options['client_id'], $this->options['client_secret']);
    }
}