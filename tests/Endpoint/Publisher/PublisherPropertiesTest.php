<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Endpoint\Publisher;

use ClosePartnerSdk\Dto\ItemFlowProperty;
use ClosePartnerSdk\Dto\PublisherId;
use ClosePartnerSdk\Dto\UserId;
use ClosePartnerSdk\Tests\Endpoint\EndpointTestCase;
use Http\Message\RequestMatcher\RequestMatcher;
use Psr\Http\Message\RequestInterface;

class PublisherPropertiesTest extends EndpointTestCase
{
    private PublisherId $publisherId;
    private UserId $userId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->publisherId = new PublisherId('CLPU1234567890');
        $this->userId = new UserId('CLUS1234567890');
        $this->givenAnAuthorisedClient();
    }

    private function path(): string
    {
        return '/api/v1/publishers/' . $this->publisherId . '/users/' . $this->userId . '/properties';
    }

    /** @test */
    public function read_the_properties_from_a_bare_list()
    {
        $this->mockClient
            ->on(
                new RequestMatcher(preg_quote($this->path(), '/')),
                function (RequestInterface $request) {
                    self::assertEquals('GET', $request->getMethod());
                    self::assertEquals($this->path(), $request->getUri()->getPath());

                    return $this->mockResponse([
                        ['key' => 'favourite_colour', 'value' => 'blue'],
                        ['key' => 'language', 'value' => 'nl'],
                    ]);
                }
            );

        $items = $this->givenSdk()->publisher()->getProperties($this->publisherId, $this->userId);

        self::assertCount(2, $items);
        self::assertContainsOnlyInstancesOf(ItemFlowProperty::class, $items);
        self::assertEquals('favourite_colour', $items[0]->getKey());
        self::assertEquals('blue', $items[0]->getValue());
        self::assertEquals('language', $items[1]->getKey());
        self::assertEquals('nl', $items[1]->getValue());
    }

    /** @test */
    public function an_empty_list_yields_no_properties()
    {
        $this->mockClient
            ->on(
                new RequestMatcher(preg_quote($this->path(), '/')),
                fn() => $this->mockResponse([])
            );

        self::assertSame(
            [],
            $this->givenSdk()->publisher()->getProperties($this->publisherId, $this->userId)
        );
    }

    /** @test */
    public function store_the_properties_inside_an_items_envelope()
    {
        $this->mockClient
            ->on(
                new RequestMatcher(preg_quote($this->path(), '/')),
                function (RequestInterface $request) {
                    self::assertEquals('POST', $request->getMethod());
                    self::assertEquals($this->path(), $request->getUri()->getPath());
                    self::assertEquals(
                        [
                            'items' => [
                                ['key' => 'favourite_colour', 'value' => 'blue'],
                                ['key' => 'language', 'value' => 'nl'],
                            ],
                        ],
                        json_decode($request->getBody()->getContents(), true)
                    );

                    return $this->mockResponse([]);
                }
            );

        $this->givenSdk()->publisher()->setProperties($this->publisherId, $this->userId, [
            new ItemFlowProperty('favourite_colour', 'blue'),
            new ItemFlowProperty('language', 'nl'),
        ]);
    }
}
