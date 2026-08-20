<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Dto\Mapper;

use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\Product;
use ClosePartnerSdk\Dto\ProductGroup;
use DateTimeInterface;

final class ImportProductsMapper
{
    public static function forProductGroupAndEvent(ProductGroup $productGroup, EventId $eventId): array
    {
        return [
            'clev' => (string)$eventId,
            'product_group' => self::forProductGroup($productGroup),
        ];
    }

    private static function forProductGroup(ProductGroup $productGroup): array
    {
        $properties = [
            'contact_phone_number' => $productGroup->getPhoneNumber(),
            'products' => array_map(static function (Product $product) {
                return self::forProduct($product);
            }, $productGroup->getProducts()),
        ];

        if ($productGroup->getOrderId() !== null) {
            $properties['order_id'] = $productGroup->getOrderId();
        }

        return $properties;
    }

    private static function forProduct(Product $product): array
    {
        $properties = [
            'scan_code' => $product->getScanCode(),
            'product_title' => $product->getProductTitle(),
            'event_start_date_time' => $product->getEventStartDateTime()->format(DateTimeInterface::W3C),
            'number_of_items' => $product->getNumberOfItems(),
            'price' => $product->getPrice()->toArray(),
        ];

        if ($product->getProductId() !== null) {
            $properties['product_id'] = $product->getProductId();
        }

        if ($product->getProductDescription() !== null) {
            $properties['product_description'] = $product->getProductDescription();
        }

        return $properties;
    }
}
