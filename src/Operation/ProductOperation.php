<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Operation;

use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\Mapper\ImportProductsMapper;
use ClosePartnerSdk\Dto\ProductGroup;
use ClosePartnerSdk\HttpClient\Message\RequestBodyMediator;

final class ProductOperation extends CloseOperation
{
    /**
     * @throws \Http\Client\Exception
     */
    public function import(EventId $eventId, ProductGroup $productGroup): void
    {
        $this->sdk
            ->getHttpClient()
            ->post(
                $this->buildUriWithLatestVersion('/products/import'),
                [],
                RequestBodyMediator::convertStreamFromArray(
                    $this->sdk,
                    ImportProductsMapper::forProductGroupAndEvent($productGroup, $eventId)
                )
            );
    }
}
