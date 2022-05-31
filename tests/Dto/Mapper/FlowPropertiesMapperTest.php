<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Dto\Mapper;

use ClosePartnerSdk\Dto\ItemFlowProperty;
use ClosePartnerSdk\Dto\Mapper\FlowPropertiesMapper;
use PHPUnit\Framework\TestCase;

class FlowPropertiesMapperTest extends TestCase
{
    /** @test * */
    public function provide_valid_values_in_the_with_properties_request()
    {
        $key1 = 'one';
        $value1 = 'value one';
        $key2 = 'two';
        $value2 = 'value two';

        $itemFlowProperty1 = new ItemFlowProperty($key1, $value1);
        $itemFlowProperty2 = new ItemFlowProperty($key2, $value2);

        $itemFlowProperties = [$itemFlowProperty1, $itemFlowProperty2];

        $request = FlowPropertiesMapper::withProperties($itemFlowProperties);

        $items = $request['items'];

        $this->assertEquals($key1, $items[0]['key']);
        $this->assertEquals($value1, $items[0]['value']);
        $this->assertEquals($key2, $items[1]['key']);
        $this->assertEquals($value2, $items[1]['value']);
    }

    /** @test * */
    public function provide_valid_values_in_the_render_request()
    {
        $text = 'Hi {user.nickname}';

        $request = FlowPropertiesMapper::render($text);

        $this->assertEquals($text, $request['text']);
    }
}
