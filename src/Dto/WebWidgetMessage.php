<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Dto;

/**
 * A web widget message carries either a url or a block of html, never both.
 */
class WebWidgetMessage
{
    private string $width;
    private string $height;
    private ?string $url;
    private ?string $html;
    private ?string $pushNotificationMessage;

    private function __construct(string $width, string $height, ?string $url, ?string $html)
    {
        $this->width = $width;
        $this->height = $height;
        $this->url = $url;
        $this->html = $html;
        $this->pushNotificationMessage = null;
    }

    public static function withUrl(string $width, string $height, string $url): self
    {
        return new self($width, $height, $url, null);
    }

    public static function withHtml(string $width, string $height, string $html): self
    {
        return new self($width, $height, null, $html);
    }

    public function withPushNotificationMessage(string $pushNotificationMessage): self
    {
        $newInstance = clone $this;
        $newInstance->pushNotificationMessage = $pushNotificationMessage;

        return $newInstance;
    }

    public function getWidth(): string
    {
        return $this->width;
    }

    public function getHeight(): string
    {
        return $this->height;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function getHtml(): ?string
    {
        return $this->html;
    }

    public function getPushNotificationMessage(): ?string
    {
        return $this->pushNotificationMessage;
    }

    public function toArray(): array
    {
        $properties = [
            'width' => $this->width,
            'height' => $this->height,
        ];

        if ($this->url !== null) {
            $properties['url'] = $this->url;
        }

        if ($this->html !== null) {
            $properties['html'] = $this->html;
        }

        if ($this->pushNotificationMessage !== null) {
            $properties['push_notification_message'] = $this->pushNotificationMessage;
        }

        return $properties;
    }
}
