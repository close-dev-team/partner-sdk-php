<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Dto;

class ProductGroup
{
    private string $phoneNumber;
    private array $products;
    private ?string $orderId = null;

    public function __construct(string $phoneNumber, Product ...$products)
    {
        $this->phoneNumber = $phoneNumber;
        $this->products = $products;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    /**
     * @return Product[]
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    public function addProduct(Product $product): void
    {
        $this->products[] = $product;
    }

    public function withOrderId(string $orderId): self
    {
        $newInstance = clone $this;
        $newInstance->orderId = $orderId;

        return $newInstance;
    }

    public function getOrderId(): ?string
    {
        return $this->orderId;
    }
}
