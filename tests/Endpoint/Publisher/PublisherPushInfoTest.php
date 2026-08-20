<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Endpoint\Publisher;

use ClosePartnerSdk\Dto\PublisherId;
use ClosePartnerSdk\Dto\PushInfo;
use ClosePartnerSdk\Tests\Endpoint\EndpointTestCase;
use Http\Message\RequestMatcher\RequestMatcher;
use Psr\Http\Message\RequestInterface;

class PublisherPushInfoTest extends EndpointTestCase
{
    private PublisherId $publisherId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->publisherId = new PublisherId('CLPU1234567890');
        $this->givenAnAuthorisedClient();
    }

    /** @test */
    public function store_google_push_credentials()
    {
        $this->mockClient
            ->on(
                new RequestMatcher('/publishers/' . $this->publisherId . '/push_info'),
                function (RequestInterface $request) {
                    self::assertEquals('POST', $request->getMethod());
                    self::assertEquals(
                        '/api/v1/publishers/' . $this->publisherId . '/push_info',
                        $request->getUri()->getPath()
                    );
                    self::assertEquals(
                        ['google_service_encoded' => 'base64-google'],
                        json_decode($request->getBody()->getContents(), true)
                    );

                    return $this->mockResponse([]);
                }
            );

        $this->givenSdk()->publisher()->setPushInfo(
            $this->publisherId,
            PushInfo::forGoogle('base64-google')
        );
    }

    /** @test */
    public function store_apple_push_credentials_as_a_group()
    {
        $this->mockClient
            ->on(
                new RequestMatcher('/publishers/' . $this->publisherId . '/push_info'),
                function (RequestInterface $request) {
                    self::assertEquals(
                        [
                            'apple_key_id' => 'KEY123',
                            'apple_team_id' => 'TEAM123',
                            'apple_key_encoded' => 'base64-apple',
                        ],
                        json_decode($request->getBody()->getContents(), true)
                    );

                    return $this->mockResponse([]);
                }
            );

        $this->givenSdk()->publisher()->setPushInfo(
            $this->publisherId,
            PushInfo::forApple('KEY123', 'TEAM123', 'base64-apple')
        );
    }

    /** @test */
    public function store_both_platforms_at_once()
    {
        $this->mockClient
            ->on(
                new RequestMatcher('/publishers/' . $this->publisherId . '/push_info'),
                function (RequestInterface $request) {
                    self::assertEquals(
                        [
                            'google_service_encoded' => 'base64-google',
                            'apple_key_id' => 'KEY123',
                            'apple_team_id' => 'TEAM123',
                            'apple_key_encoded' => 'base64-apple',
                        ],
                        json_decode($request->getBody()->getContents(), true)
                    );

                    return $this->mockResponse([]);
                }
            );

        $this->givenSdk()->publisher()->setPushInfo(
            $this->publisherId,
            PushInfo::forGoogle('base64-google')->withApple('KEY123', 'TEAM123', 'base64-apple')
        );
    }

    /** @test */
    public function delete_the_push_credentials()
    {
        $called = false;

        $this->mockClient
            ->on(
                new RequestMatcher('/publishers/' . $this->publisherId . '/push_info'),
                function (RequestInterface $request) use (&$called) {
                    $called = true;
                    self::assertEquals('DELETE', $request->getMethod());
                    self::assertEquals(
                        '/api/v1/publishers/' . $this->publisherId . '/push_info',
                        $request->getUri()->getPath()
                    );
                    self::assertEmpty($request->getBody()->getContents());

                    return $this->mockResponse([]);
                }
            );

        $this->givenSdk()->publisher()->deletePushInfo($this->publisherId);

        self::assertTrue($called);
    }

    /** @test */
    public function adding_apple_leaves_the_original_google_only_info_alone()
    {
        $google = PushInfo::forGoogle('base64-google');
        $google->withApple('KEY123', 'TEAM123', 'base64-apple');

        self::assertNull($google->getAppleKeyId());
        self::assertArrayNotHasKey('apple_key_id', $google->toArray());
    }
}
