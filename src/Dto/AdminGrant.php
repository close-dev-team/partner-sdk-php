<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Dto;

/**
 * The result of granting admin rights by phone number.
 *
 * Both outcomes are successes. The response names no user by design: it
 * reports what happened, never who the number belongs to.
 */
class AdminGrant
{
    /** The number belongs to a user, who is an admin now. */
    public const OUTCOME_GRANTED = 'granted';

    /** No account yet; they become admin when that number registers. */
    public const OUTCOME_PENDING = 'pending';

    private string $outcome;
    private bool $added;

    public function __construct(string $outcome, bool $added)
    {
        $this->outcome = $outcome;
        $this->added = $added;
    }

    /**
     * @return string one of the OUTCOME_ constants
     */
    public function getOutcome(): string
    {
        return $this->outcome;
    }

    /**
     * False when the grant was already in place: the call is idempotent, and
     * repeating it changes nothing.
     */
    public function isAdded(): bool
    {
        return $this->added;
    }

    public function isGranted(): bool
    {
        return $this->outcome === self::OUTCOME_GRANTED;
    }

    /**
     * True when no account exists for the number yet. A normal result, not
     * an error: the grant takes effect once that number registers.
     */
    public function isPending(): bool
    {
        return $this->outcome === self::OUTCOME_PENDING;
    }

    public static function buildFromResponseObject(\StdClass $obj): self
    {
        return new self($obj->outcome, (bool)($obj->added ?? false));
    }
}
