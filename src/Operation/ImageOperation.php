<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Operation;

use ClosePartnerSdk\Dto\ImageId;
use ClosePartnerSdk\Exception\FileNotReadableException;
use ClosePartnerSdk\Exception\MissingResponsePropertiesException;
use Http\Message\MultipartStream\MultipartStreamBuilder;

final class ImageOperation extends CloseOperation
{
    /**
     * Upload an image so it can be referenced by the image message endpoints.
     *
     * The endpoint takes multipart/form-data rather than json, so the body is
     * built here instead of going through RequestBodyMediator.
     *
     * @throws FileNotReadableException
     * @throws MissingResponsePropertiesException
     * @throws \Http\Client\Exception
     * @throws \JsonException
     */
    public function upload(string $filePath, string $mimeType): ImageId
    {
        if (!is_file($filePath) || !is_readable($filePath)) {
            throw new FileNotReadableException(
                sprintf('The image at %s can not be read.', $filePath)
            );
        }

        return $this->uploadContents(
            (string)file_get_contents($filePath),
            basename($filePath),
            $mimeType
        );
    }

    /**
     * @throws MissingResponsePropertiesException
     * @throws \Http\Client\Exception
     * @throws \JsonException
     */
    public function uploadContents(string $contents, string $fileName, string $mimeType): ImageId
    {
        $builder = new MultipartStreamBuilder($this->sdk->getStreamFactory());
        $builder->addResource('mime_type', $mimeType);
        $builder->addResource('file', $contents, [
            'filename' => $fileName,
            'headers' => ['Content-Type' => $mimeType],
        ]);

        $response = $this->sdk
            ->getHttpClient()
            ->post(
                $this->buildUriWithLatestVersion('/images'),
                ['Content-Type' => 'multipart/form-data; boundary="' . $builder->getBoundary() . '"'],
                $builder->build()
            );

        $obj = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);
        if (!isset($obj->image_id)) {
            throw MissingResponsePropertiesException::forProperty('/images', 'image_id');
        }

        return new ImageId($obj->image_id);
    }
}
