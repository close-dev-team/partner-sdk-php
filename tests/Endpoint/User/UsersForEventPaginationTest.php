<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Endpoint\User;

use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Tests\Endpoint\EndpointTestCase;
use Http\Message\RequestMatcher\RequestMatcher;
use Psr\Http\Message\RequestInterface;

class UsersForEventPaginationTest extends EndpointTestCase
{
    private EventId $eventId;

    /** @var string[] */
    private array $queriesSeen = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->eventId = new EventId('CLEV1234567890');
        $this->queriesSeen = [];
        $this->givenAnAuthorisedClient();
    }

    /**
     * Serves `$perPage` users per page across `$lastPage` pages, recording the
     * query string of every request so the paging can be asserted.
     */
    private function givenPagesOfUsers(int $lastPage, int $perPage = 2, bool $withMeta = true): void
    {
        $this->mockClient
            ->on(
                new RequestMatcher('/events/' . $this->eventId . '/users'),
                function (RequestInterface $request) use ($lastPage, $perPage, $withMeta) {
                    $this->queriesSeen[] = $request->getUri()->getQuery();

                    parse_str($request->getUri()->getQuery(), $query);
                    $page = (int)($query['page'] ?? 1);

                    $data = [];
                    for ($i = 1; $i <= $perPage; $i++) {
                        $data[] = ['user_id' => sprintf('CLUS-p%d-u%d', $page, $i)];
                    }

                    $payload = ['data' => $data];
                    if ($withMeta) {
                        $payload['meta'] = ['current_page' => $page, 'last_page' => $lastPage];
                    }

                    return $this->mockResponse($payload);
                }
            );
    }

    /** @test */
    public function walk_every_page_of_a_paginated_event()
    {
        $this->givenPagesOfUsers(3);

        $users = $this->givenSdk()->user()->getUsersForEvent($this->eventId);

        self::assertCount(6, $users);
        self::assertEquals('CLUS-p1-u1', (string)$users[0]->getUserId());
        self::assertEquals('CLUS-p3-u2', (string)$users[5]->getUserId());
        self::assertEquals(['', 'page=2', 'page=3'], $this->queriesSeen);
    }

    /** @test */
    public function ask_for_one_page_only_when_there_is_one_page()
    {
        $this->givenPagesOfUsers(1);

        $users = $this->givenSdk()->user()->getUsersForEvent($this->eventId);

        self::assertCount(2, $users);
        self::assertEquals([''], $this->queriesSeen);
    }

    /** @test */
    public function stop_after_one_request_when_the_response_carries_no_meta()
    {
        $this->givenPagesOfUsers(3, 2, false);

        $users = $this->givenSdk()->user()->getUsersForEvent($this->eventId);

        self::assertCount(2, $users);
        self::assertEquals([''], $this->queriesSeen);
    }

    /** @test */
    public function fetch_a_single_page_by_hand()
    {
        $this->givenPagesOfUsers(4);

        $page = $this->givenSdk()->user()->getUsersForEventPage($this->eventId, 2);

        self::assertCount(2, $page->getUsers());
        self::assertEquals(2, $page->getCurrentPage());
        self::assertEquals(4, $page->getLastPage());
        self::assertTrue($page->hasMorePages());
        self::assertEquals(['page=2'], $this->queriesSeen);
    }

    /** @test */
    public function the_last_page_reports_no_more_pages()
    {
        $this->givenPagesOfUsers(4);

        $page = $this->givenSdk()->user()->getUsersForEventPage($this->eventId, 4);

        self::assertFalse($page->hasMorePages());
    }
}
