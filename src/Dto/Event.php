<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Dto;

class Event
{
    private EventId $eventId;
    private string $publisherName;
    private string $code;
    private string $startDateTime;
    private string $endDateTime;
    private string $name;
    private string $venue;
    private string $chatNickname;
    private string $photoImageUrl;
    private string $backgroundImageUrl;
    private string $chatMessageBackgroundColor;
    private string $chatMessageTextColor;
    private string $currency;
    private string $timeZone;
    private string $locale;

    public function __construct(
        EventId $eventId,
        string  $publisherName,
        string  $code,
        string  $startDateTime,
        string  $endDateTime,
        string  $name,
        string  $venue,
        string  $chatNickname,
        string  $photoImageUrl,
        string  $backgroundImageUrl,
        string  $chatMessageBackgroundColor,
        string  $chatMessageTextColor,
        string  $currency,
        string  $timeZone,
        string  $locale
    )
    {
        $this->eventId = $eventId;
        $this->publisherName = $publisherName;
        $this->code = $code;
        $this->startDateTime = $startDateTime;
        $this->endDateTime = $endDateTime;
        $this->name = $name;
        $this->venue = $venue;
        $this->chatNickname = $chatNickname;
        $this->photoImageUrl = $photoImageUrl;
        $this->backgroundImageUrl = $backgroundImageUrl;
        $this->chatMessageBackgroundColor = $chatMessageBackgroundColor;
        $this->chatMessageTextColor = $chatMessageTextColor;
        $this->currency = $currency;
        $this->timeZone = $timeZone;
        $this->locale = $locale;
    }

    public static function buildFromRepsonseObject(\StdClass $obj): self
    {
        return new self(
            new EventId($obj->event_id),
            $obj->publisher_name,
            $obj->code,
            $obj->start_date_time,
            $obj->end_date_time,
            $obj->name,
            $obj->venue,
            $obj->chat_nickname,
            $obj->photo_image_url,
            $obj->background_image_url,
            $obj->chat_message_background_color,
            $obj->chat_message_text_color,
            $obj->currency,
            $obj->time_zone,
            $obj->locale
        );
    }

    public function getEventId(): EventId
    {
        return $this->eventId;
    }

    public function getPublisherName(): string
    {
        return $this->publisherName;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getStartDateTime(): string
    {
        return $this->startDateTime;
    }

    public function getEndDateTime(): string
    {
        return $this->endDateTime;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getVenue(): string
    {
        return $this->venue;
    }

    public function getChatNickname(): string
    {
        return $this->chatNickname;
    }

    public function getPhotoImageUrl(): string
    {
        return $this->photoImageUrl;
    }

    public function getBackgroundImageUrl(): string
    {
        return $this->backgroundImageUrl;
    }

    public function getChatMessageBackgroundColor(): string
    {
        return $this->chatMessageBackgroundColor;
    }

    public function getChatMessageTextColor(): string
    {
        return $this->chatMessageTextColor;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getTimeZone(): string
    {
        return $this->timeZone;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }
}