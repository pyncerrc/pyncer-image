<?php
namespace Pyncer\Image\Driver\GD;

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
            imagedestroy($handle);
        }

        return parent::setHandle($value);
    }

    public function new(
        int $width,
        int $height,
        ?Color $background = null,
    ): static
    {
        $image = imagecreatetruecolor($width, $height);

        if ($background === null) {
            $background = new Color();
        }

        if ($background->getBitrate() != 32) {
            $background = $background->withBitrate(32);
        }

        $color = imagecolorallocatealpha(
            $image,
            $background->getRed(),
            $background->getGreen(),
            $background->getBlue(),
            127 - floor($background->getAlpha() / 2)
        );

        imagefill($image, 0, 0, $color);

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
                return imagecreatefromjpeg($file);
            case ImageFormat::PNG:
                return imagecreatefrompng($file);
            case ImageFormat::GIF:
                return imagecreatefromgif($file);
            case ImageFormat::WEBP:
                return imagecreatefromwebp($file);
            case ImageFormat::AVIF:
                return imagecreatefromavif($file);
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
        ob_start();
        imagejpeg(
            $this->getHandle(),
            null,
            $this->getQuality()
        );
        return ob_get_clean();
    }
    protected function encodePng(): mixed
    {
        $handle = $this->getHandle();
        imagealphablending($handle, false);
        imagesavealpha($handle, true);

        ob_start();
        imagepng($handle, null, -1);
        return ob_get_clean();
    }
    protected function encodeGif(): mixed
    {
        ob_start();
        imagegif($this->getHandle());
        return ob_get_clean();
    }
    protected function encodeWebp(): mixed
    {
        $handle = $this->getHandle();
        imagealphablending($handle, false);
        imagesavealpha($handle, true);

        ob_start();
        imagewebp(
            $handle,
            null,
            $this->getQuality()
        );
        return ob_get_clean();
    }
    protected function encodeAvif(): mixed
    {
        $handle = $this->getHandle();
        imagealphablending($handle, false);
        imagesavealpha($handle, true);

        ob_start();
        imageavif(
            $handle,
            null,
            $this->getQuality(),
            $this->getSpeed()
        );
        return ob_get_clean();
    }

    public function getSize(): array
    {
        $handle = $this->getHandle();

        return [
            imagesx($handle),
            imagesy($handle)
        ];
    }
}
