<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Dto\Mapper;

final class SendMessageMapper
{
    public static function withText(string $text): array
    {
        return [
            'text' => $text,
        ];
    }
}