<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests;

use ClosePartnerSdk\CloseSdk;
use ClosePartnerSdk\Options;
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
    public function provide_default_values_for_cient_builder_and_uri_and_version()
    {
        $options = new Options([
            'client_id' => '1234',
            'client_secret' => 'secret',
        ]);

        self::assertNotEmpty($options->getClientBuilder());
        self::assertNotEmpty($options->getUriFactory());
        self::assertEquals(CloseSdk::LATEST_VERSION, $options->getVersion());
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
}
