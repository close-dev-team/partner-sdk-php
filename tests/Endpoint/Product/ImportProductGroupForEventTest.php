<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Endpoint\Product;

use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\Mapper\ImportProductsMapper;
use ClosePartnerSdk\Tests\Endpoint\EndpointTestCase;
use ClosePartnerSdk\Tests\Factory\Dto\ProductGroupFactory;
use Http\Message\RequestMatcher\RequestMatcher;
use Psr\Http\Message\RequestInterface;

class ImportProductGroupForEventTest extends EndpointTestCase
{
    private EventId $eventId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->eventId = new EventId('CLEV1234567890');
        $this->givenAnAuthorisedClient();
    }

    /** @test */
    public function post_the_product_group_to_the_import_endpoint()
    {
        $productGroup = ProductGroupFactory::createWithProductDetails();

        $this->mockClient
            ->on(
                new RequestMatcher('/products/import'),
                function (RequestInterface $request) use ($productGroup) {
                    self::assertEquals('POST', $request->getMethod());
                    self::assertEquals('/api/v1/products/import', $request->getUri()->getPath());
                    self::assertEquals(
                        ImportProductsMapper::forProductGroupAndEvent($productGroup, $this->eventId),
                        json_decode($request->getBody()->getContents(), true)
                    );

                    return $this->mockResponse([]);
                }
            );

        $this->givenSdk()->product()->import($this->eventId, $productGroup);
    }

    /** @test */
    public function the_price_survives_the_round_trip_through_json()
    {
        $this->mockClient
            ->on(
                new RequestMatcher('/products/import'),
                function (RequestInterface $request) {
                    $body = json_decode($request->getBody()->getContents(), true);

                    self::assertEquals(
                        ['currency' => 'EUR', 'amount' => 30.4],
                        $body['product_group']['products'][0]['price']
                    );

                    return $this->mockResponse([]);
                }
            );

        $this->givenSdk()->product()->import(
            $this->eventId,
            ProductGroupFactory::createWithOneProduct()
        );
    }
}
