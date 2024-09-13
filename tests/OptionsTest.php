<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests;

use ClosePartnerSdk\Options;
use ClosePartnerSdk\Config;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

class OptionsTest extends TestCase
{
    /** @test **/
    public function inform_when_client_id_is_missing_in_options()
    {
        $this->expectException(MissingOptionsException::class);

        new Options([
            'client_secret' => 'secret',
        ]);
    }

    /** @test **/
    public function inform_when_client_secret_is_missing_in_options()
    {
        $this->expectException(MissingOptionsException::class);

        new Options([
            'client_id' => '1234',
        ]);
    }

    /** @test **/
    public function provide_default_values_for_cient_builder_and_uri()
    {
        $options = new Options([
            'client_id' => '1234',
            'client_secret' => 'secret',
        ]);

        self::assertNotEmpty($options->getClientBuilder());
        self::assertNotEmpty($options->getUriFactory());
    }

    public function return_credentials_provided_by_the_constructor()
    {
        $clientId = '1234';
        $clientSecret = 'secret';
        $options = new Options([
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
        ]);

        $authCredentials = $options->getAuthCredentials();
        self::assertEquals($clientId, $authCredentials->getClientId());
        self::assertEquals($clientSecret, $authCredentials->getClientSecret());
    }

    /** @test **/
    public function provide_default_base_uri_for_production_endpoint(): void
    {
        $options = new Options([
            'client_id' => '1234',
            'client_secret' => 'secret',
        ]);

        self::assertNotEmpty($options->getBaseUri());
        self::assertEquals(Config::BASE_URI, $options->getBaseUri());
    }

    /** @test **/
    public function provide_default_custom_endpoint_for_testing_or_sdk_platforms(): void
    {
        $options = new Options([
            'client_id' => '1234',
            'client_secret' => 'secret',
            'base_uri' => 'http://localhost:8080/api',
        ]);

        self::assertNotEmpty($options->getBaseUri());
        self::assertEquals('http://localhost:8080/api', $options->getBaseUri());
    }
}
