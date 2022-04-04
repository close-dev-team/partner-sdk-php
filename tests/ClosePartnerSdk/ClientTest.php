<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests;

use ClosePartnerSdk\Client;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    private Client $client;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = new Client;
    }

    /** @test **/
    public function connect_successfully()
    {
        self::assertTrue($this->client->connect());
    }

    public function tearDown(): void
    {
        unset($this->client);
        parent::tearDown();
    }
}
