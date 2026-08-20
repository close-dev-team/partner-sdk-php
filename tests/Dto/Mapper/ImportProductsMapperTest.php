<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Dto\Mapper;

use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\Mapper\ImportProductsMapper;
use ClosePartnerSdk\Tests\Factory\Dto\ProductGroupFactory;
use DateTime;
use PHPUnit\Framework\TestCase;

class ImportProductsMapperTest extends TestCase
{
    /** @test */
    public function provide_event_id_in_the_request()
    {
        $eventId = new EventId('CLEV1234567890');

        $request = ImportProductsMapper::forProductGroupAndEvent(
            ProductGroupFactory::createWithoutProducts(),
            $eventId
        );

        self::assertEquals((string)$eventId, $request['clev']);
    }

    /** @test */
    public function provide_phone_number_in_request()
    {
        $request = ImportProductsMapper::forProductGroupAndEvent(
            ProductGroupFactory::createWithoutProducts(),
            new EventId('CLEV1234567890')
        );

        self::assertEquals(
            ProductGroupFactory::PHONE_NUMBER,
            $request['product_group']['contact_phone_number']
        );
    }

    /** @test */
    public function provide_product_with_minimal_info_in_request()
    {
        $request = ImportProductsMapper::forProductGroupAndEvent(
            ProductGroupFactory::createWithOneProduct(),
            new EventId('CLEV1234567890')
        );

        $product = $request['product_group']['products'][0];
        self::assertEquals('ABC123123', $product['scan_code']);
        self::assertEquals('Fresh beer in the bar.', $product['product_title']);
        self::assertEquals(
            (new DateTime(ProductGroupFactory::START_DATE_TIME))->format(DateTime::W3C),
            $product['event_start_date_time']
        );
        self::assertEquals(1, $product['number_of_items']);
    }

    /** @test */
    public function the_price_is_always_sent_as_a_currency_and_an_amount()
    {
        $request = ImportProductsMapper::forProductGroupAndEvent(
            ProductGroupFactory::createWithOneProduct(),
            new EventId('CLEV1234567890')
        );

        self::assertEquals(
            ['currency' => 'EUR', 'amount' => 30.4],
            $request['product_group']['products'][0]['price']
        );
    }

    /** @test */
    public function leave_the_optional_product_details_out_until_they_are_given()
    {
        $request = ImportProductsMapper::forProductGroupAndEvent(
            ProductGroupFactory::createWithOneProduct(),
            new EventId('CLEV1234567890')
        );

        $product = $request['product_group']['products'][0];
        self::assertArrayNotHasKey('product_id', $product);
        self::assertArrayNotHasKey('product_description', $product);
    }

    /** @test */
    public function provide_product_with_details_in_request()
    {
        $request = ImportProductsMapper::forProductGroupAndEvent(
            ProductGroupFactory::createWithProductDetails(),
            new EventId('CLEV1234567890')
        );

        $product = $request['product_group']['products'][0];
        self::assertEquals('f01c70da-e21c-4710-be0a-acb06f601769', $product['product_id']);
        self::assertEquals('Valid for a beer in the bar from the main hall.', $product['product_description']);
        self::assertEquals(3, $product['number_of_items']);
    }

    /** @test */
    public function provide_multiple_products_in_request()
    {
        $group = ProductGroupFactory::createWithMultipleProducts();

        $request = ImportProductsMapper::forProductGroupAndEvent(
            $group,
            new EventId('CLEV1234567890')
        );

        self::assertCount(count($group->getProducts()), $request['product_group']['products']);
    }

    /** @test */
    public function provide_the_order_id_only_when_it_is_set()
    {
        $withoutOrderId = ImportProductsMapper::forProductGroupAndEvent(
            ProductGroupFactory::createWithOneProduct(),
            new EventId('CLEV1234567890')
        );
        self::assertArrayNotHasKey('order_id', $withoutOrderId['product_group']);

        $withOrderId = ImportProductsMapper::forProductGroupAndEvent(
            ProductGroupFactory::createWithOneProduct()->withOrderId('ORDER-123'),
            new EventId('CLEV1234567890')
        );
        self::assertEquals('ORDER-123', $withOrderId['product_group']['order_id']);
    }
}
