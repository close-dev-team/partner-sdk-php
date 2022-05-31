<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Dto\Mapper;

use ClosePartnerSdk\Dto\ItemFlowProperty;

final class FlowPropertiesMapper
{
    /**
     * @param ItemFlowProperty[] $itemFlowProperties
     * @return string[]
     */
    public static function withProperties(array $itemFlowProperties): array
    {
        $itemFlowsBody = [];

        foreach ($itemFlowProperties as $itemFlowProperty) {
            $itemFlowsBody[] = $itemFlowProperty->toArray();
        }

        return [
            'items' => $itemFlowsBody
        ];
    }

    /**
     * @param string $text
     * @return string[]
     */
    public static function render(string $text): array
    {
        return [
            'text' => $text
        ];
    }
}