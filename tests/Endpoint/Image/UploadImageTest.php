<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Endpoint\Image;

use ClosePartnerSdk\Exception\FileNotReadableException;
use ClosePartnerSdk\Exception\MissingResponsePropertiesException;
use ClosePartnerSdk\Tests\Endpoint\EndpointTestCase;
use Http\Message\RequestMatcher\RequestMatcher;
use Psr\Http\Message\RequestInterface;

class UploadImageTest extends EndpointTestCase
{
    private ?string $filePath = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->givenAnAuthorisedClient();
    }

    protected function tearDown(): void
    {
        if ($this->filePath !== null && is_file($this->filePath)) {
            unlink($this->filePath);
        }
        parent::tearDown();
    }

    private function givenAnImageFile(string $contents = 'not-really-a-png'): string
    {
        $this->filePath = tempnam(sys_get_temp_dir(), 'close') . '.png';
        file_put_contents($this->filePath, $contents);

        return $this->filePath;
    }

    /** @test */
    public function post_the_image_as_multipart_form_data()
    {
        $filePath = $this->givenAnImageFile();

        $this->mockClient
            ->on(
                new RequestMatcher('/images'),
                function (RequestInterface $request) use ($filePath) {
                    self::assertEquals('POST', $request->getMethod());
                    self::assertEquals('/api/v1/images', $request->getUri()->getPath());
                    self::assertStringStartsWith(
                        'multipart/form-data; boundary="',
                        $request->getHeaderLine('Content-Type')
                    );

                    $body = $request->getBody()->getContents();
                    self::assertStringContainsString('name="mime_type"', $body);
                    self::assertStringContainsString('image/png', $body);
                    self::assertStringContainsString('name="file"', $body);
                    self::assertStringContainsString('filename="' . basename($filePath) . '"', $body);
                    self::assertStringContainsString('not-really-a-png', $body);

                    return $this->mockResponse(['image_id' => 'CLIM1234567890']);
                }
            );

        $imageId = $this->givenSdk()->image()->upload($filePath, 'image/png');

        self::assertEquals('CLIM1234567890', (string)$imageId);
    }

    /** @test */
    public function the_json_content_type_default_does_not_override_multipart()
    {
        $filePath = $this->givenAnImageFile();

        $this->mockClient
            ->on(
                new RequestMatcher('/images'),
                function (RequestInterface $request) {
                    self::assertStringNotContainsString(
                        'application/json',
                        $request->getHeaderLine('Content-Type')
                    );

                    return $this->mockResponse(['image_id' => 'CLIM1234567890']);
                }
            );

        $this->givenSdk()->image()->upload($filePath, 'image/png');
    }

    /** @test */
    public function upload_contents_without_touching_the_filesystem()
    {
        $this->mockClient
            ->on(
                new RequestMatcher('/images'),
                function (RequestInterface $request) {
                    $body = $request->getBody()->getContents();
                    self::assertStringContainsString('filename="poster.jpg"', $body);
                    self::assertStringContainsString('image/jpeg', $body);

                    return $this->mockResponse(['image_id' => 'CLIM9999999999']);
                }
            );

        $imageId = $this->givenSdk()->image()->uploadContents('binary', 'poster.jpg', 'image/jpeg');

        self::assertEquals('CLIM9999999999', (string)$imageId);
    }

    /** @test */
    public function refuse_a_file_that_can_not_be_read()
    {
        $this->expectException(FileNotReadableException::class);

        $this->givenSdk()->image()->upload('/tmp/there-is-no-such-image.png', 'image/png');
    }

    /** @test */
    public function complain_when_the_response_has_no_image_id()
    {
        $filePath = $this->givenAnImageFile();

        $this->mockClient
            ->on(
                new RequestMatcher('/images'),
                fn() => $this->mockResponse([])
            );

        $this->expectException(MissingResponsePropertiesException::class);

        $this->givenSdk()->image()->upload($filePath, 'image/png');
    }
}
