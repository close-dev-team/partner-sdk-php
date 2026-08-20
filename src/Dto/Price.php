<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Dto;

class Price
{
    private string $currency;
    private float $amount;

    /**
     * @param string $currency Validated by the API against the currencies
     *                         enabled for the partner, so it is passed through
     *                         as given rather than checked here.
     */
    public function __construct(string $currency, float $amount)
    {
        $this->currency = $currency;
        $this->amount = $amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function toArray(): array
    {
        return [
            'currency' => $this->currency,
            'amount' => $this->amount,
        ];
    }
}
