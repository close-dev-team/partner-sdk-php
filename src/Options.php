<?php
declare(strict_types=1);

namespace ClosePartnerSdk;

use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;
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
        $resolver->setDefaults(
            [
                'client_builder' => new ClientBuilder,
                'uri_factory' => Psr17FactoryDiscovery::findUriFactory(),
                'version' => CloseSdk::LATEST_VERSION,
            ]
        );

        $resolver->setAllowedTypes('version', 'string');
        $resolver->setAllowedTypes('client_builder', ClientBuilder::class);
        $resolver->setAllowedTypes('uri_factory', UriFactoryInterface::class);
    }

    public function getClientBuilder(): ClientBuilder
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
}