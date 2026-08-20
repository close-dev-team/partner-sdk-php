<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Endpoint\Event;

use ClosePartnerSdk\Dto\AdminGrant;
use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\UserId;
use ClosePartnerSdk\Tests\Endpoint\EndpointTestCase;
use Http\Message\RequestMatcher\RequestMatcher;
use Psr\Http\Message\RequestInterface;

class EventAdminsTest extends EndpointTestCase
{
    private EventId $eventId;
    private UserId $userId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->eventId = new EventId('CLEV1234567890');
        $this->userId = new UserId('CLUS1234567890');
        $this->givenAnAuthorisedClient();
    }

    /** @test */
    public function add_a_user_as_admin()
    {
        $this->mockClient
            ->on(
                new RequestMatcher('/admins/' . $this->userId),
                function (RequestInterface $request) {
                    self::assertEquals('POST', $request->getMethod());
                    self::assertEquals(
                        '/api/v1/events/' . $this->eventId . '/admins/' . $this->userId,
                        $request->getUri()->getPath()
                    );
                    self::assertEmpty($request->getBody()->getContents());

                    return $this->mockResponse(['added' => true]);
                }
            );

        self::assertTrue($this->givenSdk()->event()->addAdmin($this->eventId, $this->userId));
    }

    /** @test */
    public function adding_an_existing_admin_reports_that_nothing_changed()
    {
        $this->mockClient
            ->on(
                new RequestMatcher('/admins/' . $this->userId),
                fn() => $this->mockResponse(['added' => false])
            );

        self::assertFalse($this->givenSdk()->event()->addAdmin($this->eventId, $this->userId));
    }

    /** @test */
    public function remove_a_user_as_admin()
    {
        $this->mockClient
            ->on(
                new RequestMatcher('/admins/' . $this->userId),
                function (RequestInterface $request) {
                    self::assertEquals('DELETE', $request->getMethod());
                    self::assertEquals(
                        '/api/v1/events/' . $this->eventId . '/admins/' . $this->userId,
                        $request->getUri()->getPath()
                    );

                    return $this->mockResponse(['deleted' => true]);
                }
            );

        self::assertTrue($this->givenSdk()->event()->removeAdmin($this->eventId, $this->userId));
    }

    /** @test */
    public function removing_someone_who_is_not_an_admin_reports_that_nothing_changed()
    {
        $this->mockClient
            ->on(
                new RequestMatcher('/admins/' . $this->userId),
                fn() => $this->mockResponse(['deleted' => false])
            );

        self::assertFalse($this->givenSdk()->event()->removeAdmin($this->eventId, $this->userId));
    }

    /** @test */
    public function grant_admin_by_phone_number_to_an_existing_user()
    {
        $this->mockClient
            ->on(
                new RequestMatcher('/admins/by-phone'),
                function (RequestInterface $request) {
                    self::assertEquals('POST', $request->getMethod());
                    self::assertEquals(
                        '/api/v1/events/' . $this->eventId . '/admins/by-phone',
                        $request->getUri()->getPath()
                    );
                    self::assertEquals(
                        ['phone_number' => '+31612345678'],
                        json_decode($request->getBody()->getContents(), true)
                    );

                    return $this->mockResponse(['outcome' => 'granted', 'added' => true]);
                }
            );

        $grant = $this->givenSdk()->event()->addAdminByPhoneNumber($this->eventId, '+31612345678');

        self::assertEquals(AdminGrant::OUTCOME_GRANTED, $grant->getOutcome());
        self::assertTrue($grant->isGranted());
        self::assertFalse($grant->isPending());
        self::assertTrue($grant->isAdded());
    }

    /** @test */
    public function a_number_without_an_account_yet_is_pending_and_not_a_failure()
    {
        $this->mockClient
            ->on(
                new RequestMatcher('/admins/by-phone'),
                fn() => $this->mockResponse(['outcome' => 'pending', 'added' => true])
            );

        $grant = $this->givenSdk()->event()->addAdminByPhoneNumber($this->eventId, '+31687654321');

        self::assertEquals(AdminGrant::OUTCOME_PENDING, $grant->getOutcome());
        self::assertTrue($grant->isPending());
        self::assertFalse($grant->isGranted());
        self::assertTrue($grant->isAdded());
    }

    /** @test */
    public function repeating_a_grant_that_already_took_effect_adds_nothing()
    {
        $this->mockClient
            ->on(
                new RequestMatcher('/admins/by-phone'),
                fn() => $this->mockResponse(['outcome' => 'granted', 'added' => false])
            );

        $grant = $this->givenSdk()->event()->addAdminByPhoneNumber($this->eventId, '+31612345678');

        self::assertTrue($grant->isGranted());
        self::assertFalse($grant->isAdded());
    }
}
