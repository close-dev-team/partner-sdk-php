<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Dto;

class ImageMessage
{
    private ImageId $imageId;
    private ?bool $sendPush;

    public function __construct(ImageId $imageId)
    {
        $this->imageId = $imageId;
        $this->sendPush = null;
    }

    public function withSendPush(bool $sendPush): self
    {
        $newInstance = clone $this;
        $newInstance->sendPush = $sendPush;

        return $newInstance;
    }

    public function getImageId(): ImageId
    {
        return $this->imageId;
    }

    public function getSendPush(): ?bool
    {
        return $this->sendPush;
    }

    public function toArray(): array
    {
        $properties = ['image_id' => (string)$this->imageId];

        if ($this->sendPush !== null) {
            $properties['send_push'] = $this->sendPush;
        }

        return $properties;
    }
}
