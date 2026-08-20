<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Factory\Dto;

use ClosePartnerSdk\Dto\Price;
use ClosePartnerSdk\Dto\Product;
use ClosePartnerSdk\Dto\ProductGroup;
use DateTimeImmutable;

final class ProductGroupFactory
{
    public const PHONE_NUMBER = '+31666111333';
    public const START_DATE_TIME = '2026-01-01T10:00:00+01:00';

    public static function createWithoutProducts(): ProductGroup
    {
        return new ProductGroup(self::PHONE_NUMBER);
    }

    public static function createWithOneProduct(): ProductGroup
    {
        $group = self::createWithoutProducts();
        $group->addProduct(self::minimalProduct());

        return $group;
    }

    public static function createWithProductDetails(): ProductGroup
    {
        $group = self::createWithoutProducts();
        $group->addProduct(new Product(
            'ABC123123',
            'Fresh beer in the bar.',
            new DateTimeImmutable(self::START_DATE_TIME),
            new Price('EUR', 30.4),
            3,
            'f01c70da-e21c-4710-be0a-acb06f601769',
            'Valid for a beer in the bar from the main hall.'
        ));

        return $group;
    }

    public static function createWithMultipleProducts(): ProductGroup
    {
        $group = self::createWithOneProduct();
        $group->addProduct(self::minimalProduct());

        return $group;
    }

    public static function minimalProduct(): Product
    {
        return new Product(
            'ABC123123',
            'Fresh beer in the bar.',
            new DateTimeImmutable(self::START_DATE_TIME),
            new Price('EUR', 30.4)
        );
    }
}
