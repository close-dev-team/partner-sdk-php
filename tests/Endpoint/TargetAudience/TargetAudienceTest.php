<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Endpoint\TargetAudience;

use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\TargetAudience;
use ClosePartnerSdk\Tests\Endpoint\EndpointTestCase;
use Http\Message\RequestMatcher\RequestMatcher;
use Psr\Http\Message\RequestInterface;

class TargetAudienceTest extends EndpointTestCase
{
    private const CONDITION = '({chat.users} > 2) AND ("{user.deviceType}" == "IOS")';

    private EventId $eventId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->eventId = new EventId('CLEV1234567890');
        $this->givenAnAuthorisedClient();
    }

    /** @test */
    public function create_a_target_audience()
    {
        $this->mockClient
            ->on(
                new RequestMatcher('/events/' . $this->eventId . '/target_audiences'),
                function (RequestInterface $request) {
                    self::assertEquals('POST', $request->getMethod());
                    self::assertEquals(
                        '/api/v1/events/' . $this->eventId . '/target_audiences',
                        $request->getUri()->getPath()
                    );
                    self::assertEquals(
                        ['name' => 'iOS power users', 'condition' => self::CONDITION],
                        json_decode($request->getBody()->getContents(), true)
                    );

                    return $this->mockResponse([
                        'name' => 'iOS power users',
                        'condition' => self::CONDITION,
                    ]);
                }
            );

        $created = $this->givenSdk()->targetAudience()->create(
            $this->eventId,
            new TargetAudience('iOS power users', self::CONDITION)
        );

        self::assertEquals('iOS power users', $created->getName());
        self::assertEquals(self::CONDITION, $created->getCondition());
    }

    /** @test */
    public function update_a_target_audience_by_its_current_name()
    {
        $this->mockClient
            ->on(
                new RequestMatcher('/target_audiences'),
                function (RequestInterface $request) {
                    self::assertEquals('PUT', $request->getMethod());
                    self::assertEquals(
                        '/api/v1/events/' . $this->eventId . '/target_audiences/iOS+power+users',
                        $request->getUri()->getPath()
                    );
                    self::assertEquals(
                        ['name' => 'iOS regulars', 'condition' => '{chat.users} > 5'],
                        json_decode($request->getBody()->getContents(), true)
                    );

                    return $this->mockResponse([
                        'name' => 'iOS regulars',
                        'condition' => '{chat.users} > 5',
                    ]);
                }
            );

        $updated = $this->givenSdk()->targetAudience()->update(
            $this->eventId,
            'iOS power users',
            new TargetAudience('iOS regulars', '{chat.users} > 5')
        );

        self::assertEquals('iOS regulars', $updated->getName());
        self::assertEquals('{chat.users} > 5', $updated->getCondition());
    }

    /** @test */
    public function encode_a_name_that_would_otherwise_break_the_path()
    {
        $this->mockClient
            ->on(
                new RequestMatcher('/target_audiences'),
                function (RequestInterface $request) {
                    self::assertStringContainsString(
                        '/target_audiences/50%25%2B+visitors',
                        $request->getUri()->getPath()
                    );

                    return $this->mockResponse(['name' => 'renamed', 'condition' => '{chat.users} > 1']);
                }
            );

        $this->givenSdk()->targetAudience()->update(
            $this->eventId,
            '50%+ visitors',
            new TargetAudience('renamed', '{chat.users} > 1')
        );
    }

    /** @test */
    public function the_condition_is_sent_through_untouched()
    {
        $awkward = '("{user.nickname}" == "O\'Brien") AND ({chat.users} >= 2)';

        $this->mockClient
            ->on(
                new RequestMatcher('/target_audiences'),
                function (RequestInterface $request) use ($awkward) {
                    $body = json_decode($request->getBody()->getContents(), true);
                    self::assertEquals($awkward, $body['condition']);

                    return $this->mockResponse(['name' => 'Irish', 'condition' => $awkward]);
                }
            );

        $created = $this->givenSdk()->targetAudience()->create(
            $this->eventId,
            new TargetAudience('Irish', $awkward)
        );

        self::assertEquals($awkward, $created->getCondition());
    }
}
