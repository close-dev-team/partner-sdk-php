<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Dto\Mapper;

use ClosePartnerSdk\Dto\Mapper\SendMessageMapper;
use PHPUnit\Framework\TestCase;

class SendMessageMapperTest extends TestCase
{
    /** @test * */
    public function provide_valid_values_in_the_request()
    {
        $request = SendMessageMapper::withTextAndSendPush('text', true);

        self::assertEquals('text', $request['text']);
        self::assertEquals(true, $request['send_push']);
    }
}
