<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Dto;

/**
 * One page of the paginated user collection.
 */
class UserPage
{
    private array $users;
    private int $currentPage;
    private int $lastPage;

    /**
     * @param User[] $users
     */
    public function __construct(array $users, int $currentPage, int $lastPage)
    {
        $this->users = $users;
        $this->currentPage = $currentPage;
        $this->lastPage = $lastPage;
    }

    /**
     * @return User[]
     */
    public function getUsers(): array
    {
        return $this->users;
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function getLastPage(): int
    {
        return $this->lastPage;
    }

    public function hasMorePages(): bool
    {
        return $this->currentPage < $this->lastPage;
    }
}
