<?php
namespace Pyncer\Image;

use Pyncer\Image\Exception\UnsupportedFormatException;

enum ImageFormat: string
{
    case JPEG = 'jpg';
    case PNG = 'png';
    case GIF = 'gif';
    case WEBP = 'webp';
    case AVIF = 'avif';

    public function getExtension(): string
    {
        return $this->value;
    }

    public function getMimeType(): string
    {
        switch ($this) {
            case static::JPEG;
                return 'image/jpeg';
            case static::PNG;
                return 'image/png';
            case static::GIF;
                return 'image/gif';
            case static::WEBP;
                return 'image/webp';
            case static::AVIF;
                return 'image/avif';
        }
    }

    public static function fromExtension(string $extension): static
    {
        $imageFormat = static::tryFromExtension($extension);

        if ($imageFormat === null) {
            throw new UnsupportedFormatException($extension);
        }

        return $imageFormat;
    }

    public static function fromMimeType(string $mimeType): static
    {
        $imageFormat = static::tryFromMimeType($mimeType);

        if ($imageFormat === null) {
            throw new UnsupportedFormatException($mimeType);
        }

        return $imageFormat;
    }

    public static function tryFromExtension(string $extension): ?static
    {
        switch ($extension) {
            case 'jpeg';
            case 'jpg';
                return static::JPEG;
            case 'png';
                return static::PNG;
            case 'gif';
                return static::GIF;
            case 'webp';
                return static::WEBP;
            case 'avif';
                return static::AVIF;
        }

        return null;
    }

    public static function tryFromMimeType(string $mimeType): ?static
    {
        switch ($mimeType) {
            case 'image/jpeg';
                return static::JPEG;
            case 'image/png';
                return static::PNG;
            case 'image/gif';
                return static::GIF;
            case 'image/webp';
                return static::WEBP;
            case 'image/avif';
                return static::AVIF;
        }

        return null;
    }
}
