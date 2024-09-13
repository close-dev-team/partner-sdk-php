<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Dto\Mapper;

final class SendMessageMapper
{
    public static function withTextAndSendPush(string $text, bool $sendPush): array
    {
        return [
            'text' => $text,
            'send_push' => $sendPush
        ];
    }
}