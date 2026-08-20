<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Dto\Mapper;

final class SendMessageMapper
{
    /**
     * @param bool|null $sendPush left out of the request when null, so the
     *                            API keeps applying its own default
     */
    public static function withText(string $text, ?bool $sendPush = null): array
    {
        $properties = ['text' => $text];

        if ($sendPush !== null) {
            $properties['send_push'] = $sendPush;
        }

        return $properties;
    }
}