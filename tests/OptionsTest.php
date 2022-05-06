<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests;

use ClosePartnerSdk\CloseSdk;
use ClosePartnerSdk\Options;
use PHPUnit\Framework\TestCase;

class OptionsTest extends TestCase
{
    /** @test **/
    public function provide_default_values_for_cient_builder_and_uri_and_version()
    {
        $options = new Options;

        self::assertNotEmpty($options->getClientBuilder());
        self::assertNotEmpty($options->getUriFactory());
        self::assertEquals(CloseSdk::LATEST_VERSION, $options->getVersion());
    }
}
