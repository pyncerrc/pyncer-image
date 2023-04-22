<?php
namespace Pyncer\Image\Driver\Imagick;

use Imagick;
use ImagickPixel;
use Pyncer\Image\Color;
use Pyncer\Image\Exception\UnsupportedFormatException;
use Pyncer\Image\AbstractImage;
use Pyncer\Image\ImageFormat;

class Image extends AbstractImage
{
    public function setHandle(?object $value): static
    {
        $handle = $this->getHandle();

        if ($handle !== null && $handle !== $value) {
            $handle->destroy();
        }

        return parent::setHandle($value);
    }

    public function new(
        int $width,
        int $height,
        ?Color $background = null,
    ): static
    {
        $image = new Imagick();

        if ($background === null) {
            $image->newImage(
                $width,
                $height,
                new ImagickPixel('transparent')
            );
        } else {
            $image->newImage(
                $width,
                $height,
                new ImagickPixel($background->getHex())
            );
        }

        $this->setHandle($image);
        $this->setImageFormat(ImageFormat::PNG);

        return $this;
    }

    protected function initializeHandle(
        string $file,
        ImageFormat $imageFormat
    ): mixed
    {
        switch ($imageFormat) {
            case ImageFormat::JPEG:
            case ImageFormat::PNG:
            case ImageFormat::GIF:
            case ImageFormat::WEBP:
            case ImageFormat::AVIF:
                return new Imagick($file);
        }

        throw new UnsupportedFormatException($imageFormat->getMimeType());
    }

    protected function encode(ImageFormat $imageFormat): mixed
    {
        switch ($imageFormat) {
            case ImageFormat::JPEG:
                return $this->encodeJpeg();
            case ImageFormat::PNG:
                return $this->encodePng();
            case ImageFormat::GIF:
                return $this->encodeGif();
            case ImageFormat::WEBP:
                return $this->encodeWebp();
            case ImageFormat::AVIF:
                return $this->encodeAvif();
        }

        throw new UnsupportedFormatException($imageFormat->getMimeType());
    }

    protected function encodeJpeg(): mixed
    {
        $handle = $this->getHandle();

        $handle->setImageFormat('JPEG');

        // Compression type
        $compression = $this->getDriver()->getParam(
            'imagick_jpeg_image_compression',
            Imagick::COMPRESSION_JPEG
        );
        $handle->setImageCompression($compression);

        // Quality
        $quality = $this->getDriver()->getParam(
            'imagick_jpeg_image_compression_quality',
            $this->getQuality()
        );
        $handle->setImageCompressionQuality($quality);

        return $handle->getImageBlob();
    }
    protected function encodePng(): mixed
    {
        $handle = $this->getHandle();

        $handle->setImageFormat('PNG');

        // Compression type
        $compression = $this->getDriver()->getParam(
            'imagick_png_image_compression',
            Imagick::COMPRESSION_UNDEFINED
        );
        $handle->setImageCompression($compression);

        // Quality
        $quality = $this->getDriver()->getParam(
            'imagick_png_compression_quality',
            9
        );
        $handle->setCompressionQuality($quality);

        $quality = $this->getDriver()->getParam(
            'imagick_png_image_compression_quality',
            0
        );
        $handle->setImageCompressionQuality(0);

        return $handle->getImageBlob();
    }
    protected function encodeGif(): mixed
    {
        $handle = $this->getHandle();

        $handle->setImageFormat('GIF');

        // Compression type
        $compression = $this->getDriver()->getParam(
            'imagick_gif_image_compression',
            Imagick::COMPRESSION_LZW
        );
        $handle->setImageCompression($compression);

        // Quality
        $quality = $this->getDriver()->getParam(
            'imagick_gif_image_compression_quality',
            $this->getQuality()
        );
        $handle->setImageCompressionQuality($quality);

        return $handle->getImageBlob();
    }
    protected function encodeWebp(): mixed
    {
        $handle = $this->getHandle();

        $handle->setImageFormat('WEBP');

        // Compression type
        $compression = $this->getDriver()->getParam(
            'imagick_webp_image_compression',
            Imagick::COMPRESSION_UNDEFINED
        );
        $handle->setImageCompression($compression);

        // Quality
        $quality = $this->getDriver()->getParam(
            'imagick_webp_image_compression_quality',
            $this->getQuality()
        );
        $handle->setImageCompressionQuality($quality);

        return $handle->getImageBlob();
    }
    protected function encodeAvif(): mixed
    {
        $handle = $this->getHandle();

        $handle->setImageFormat('AVIF');

        // Compression type
        $compression = $this->getDriver()->getParam(
            'imagick_avif_image_compression',
            Imagick::COMPRESSION_UNDEFINED
        );
        $handle->setImageCompression($compression);

        // Quality
        $quality = $this->getDriver()->getParam(
            'imagick_avif_image_compression_quality',
            $this->getQuality()
        );
        $handle->setImageCompressionQuality($quality);

        return $handle->getImageBlob();
    }

    public function getSize(): array
    {
        $handle = $this->getHandle();

        return [
            $handle->getImageWidth(),
            $handle->getImageHeight(),
        ];
    }
}
