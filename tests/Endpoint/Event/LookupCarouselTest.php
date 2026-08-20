<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Endpoint\Event;

use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Tests\Endpoint\EndpointTestCase;
use Http\Message\RequestMatcher\RequestMatcher;
use Psr\Http\Message\RequestInterface;

class LookupCarouselTest extends EndpointTestCase
{
    /** @test */
    public function look_the_carousel_up_by_name()
    {
        $this->givenAnAuthorisedClient();
        $eventId = new EventId('CLEV1234567890');

        $this->mockClient
            ->on(
                new RequestMatcher('/events/' . $eventId . '/carousels'),
                function (RequestInterface $request) use ($eventId) {
                    self::assertEquals('GET', $request->getMethod());
                    self::assertEquals('/api/v1/events/' . $eventId . '/carousels', $request->getUri()->getPath());
                    self::assertEquals('name=Carousel+1', $request->getUri()->getQuery());

                    return $this->mockResponse([
                        'id' => 'CLOC1234567890',
                        'name' => 'Carousel 1',
                    ]);
                }
            );

        $carousel = $this->givenSdk()->event()->lookupCarousel($eventId, 'Carousel 1');

        self::assertEquals('Carousel 1', $carousel->getName());
    }

    /** @test */
    public function encode_a_name_that_would_otherwise_break_the_query()
    {
        $this->givenAnAuthorisedClient();
        $eventId = new EventId('CLEV1234567890');

        $this->mockClient
            ->on(
                new RequestMatcher('/events/' . $eventId . '/carousels'),
                function (RequestInterface $request) {
                    self::assertEquals('name=Rock+%26+Roll', $request->getUri()->getQuery());

                    return $this->mockResponse([
                        'id' => 'CLOC1234567890',
                        'name' => 'Rock & Roll',
                    ]);
                }
            );

        $carousel = $this->givenSdk()->event()->lookupCarousel($eventId, 'Rock & Roll');

        self::assertEquals('Rock & Roll', $carousel->getName());
    }
}
