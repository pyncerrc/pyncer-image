<?php
namespace Pyncer\Image;

use Pyncer\Exception\InvalidArgumentException;
use Pyncer\Image\Color;
use Pyncer\Image\Driver;
use Pyncer\Image\Direction;
use Pyncer\Image\Exception\ImageFileNotFoundException;
use Pyncer\Image\Fit;
use Pyncer\Image\FunctionInterface;
use Pyncer\Image\ImageFormat;
use Pyncer\Image\ImageInterface;
use Pyncer\Image\Scale;
use Pyncer\Image\X;
use Pyncer\Image\Y;

use const DIRECTORY_SEPARATOR as DS;

abstract class AbstractImage implements ImageInterface
{
    protected ?string $file;
    protected ?object $handle;
    protected ImageFormat $imageFormat;
    protected int $quality;
    protected int $speed;

    public function __construct(protected Driver $driver)
    {
        $this->file = null;
        $this->handle = null;
        $this->setQuality($driver->getQuality() ?? 80);
        $this->setSpeed($driver->getSpeed() ?? 6);
        $this->setImageFormat(ImageFormat::PNG);
    }

    public function getDriver(): Driver
    {
        return $this->driver;
    }

    public function getHandle(): ?object
    {
        return $this->handle;
    }
    public function setHandle(?object $value): static
    {
        $this->handle = $value;
        return $this;
    }

    public function open(string $file): static
    {
        if (!file_exists($file)) {
            throw new ImageFileNotFoundException($file);
        }

        $this->file = $file;

        $mimeType = mime_content_type($file);
        $imageFormat = ImageFormat::fromMimeType($mimeType);

        $this->setImageFormat($imageFormat);

        $handle = $this->initializeHandle($file, $imageFormat);

        $this->setHandle($handle);

        return $this;
    }
    public function save(?string $file = null): static
    {
        if (is_null($file)) {
            $file = $this->file;

            if (is_null($file)) {
                throw new UnexpectedValueException('File path is undefined.');
            }
        }

        $this->file = $file;

        $extension = pathinfo($file)['extension'];

        if ($extension === '') {
            $imageFormat = $this->getImageFormat();
        } else {
            $imageFormat = ImageFormat::tryFromExtension($extension);
            if ($imageFormat === null) {
                $imageFormat = $this->getImageFormat();
            } else {
                $this->setImageFormat($imageFormat);
            }
        }

        file_put_contents($file, $this->encode($imageFormat));

        return $this;
    }

    protected abstract function initializeHandle(
        string $file,
        ImageFormat $imageFormat
    ): mixed;

    protected abstract function encode(ImageFormat $imageFormat): mixed;

    public function getImageFormat(): ImageFormat
    {
        return $this->imageFormat;
    }
    public function setImageFormat(ImageFormat $value): static
    {
        $this->imageFormat = $value;
        return $this;
    }

    public function getMimeType(): string
    {
        return $this->imageFormat->getMimeType();
    }

    public function getQuality(): int
    {
        return $this->quality;
    }
    public function setQuality(int $value): static
    {
        if ($value < 0 || $value > 100) {
            throw new InvalidArgumentException(
                'Quality must be between zero and 100.'
            );
        }

        $this->quality = $value;

        return $this;
    }

    public function getSpeed(): int
    {
        return $this->speed;
    }
    public function setSpeed(int $value): static
    {
        if ($value < 0 || $value > 10) {
            throw new InvalidArgumentException(
                'Speed must be between zero and 10.'
            );
        }

        $this->speed = $value;

        return $this;
    }

    public function setSize(int $width, int $height): static
    {
        return $this->resize($width, $height, Fit::FILL);
    }

    public function getWidth(): int
    {
        return $this->getSize()[0];
    }
    public function setWidth(int $value): static
    {
        return $this->setSize($value, $this->getHeight());
    }

    public function getHeight(): int
    {
        return $this->getSize()[1];
    }
    public function setHeight(int $value): static
    {
        return $this->setSize($value, $this->getHeight());
    }

    public function getPixel(int $x, int $y): Color
    {
        return $this->executeFunction('GetPixel', $x, $y);
    }
    public function setPixel(int $x, int $y, Color $color): static
    {
        return $this->executeFunction('SetPixel', $x, $y, $color);
    }

    public function resize(
        int $width,
        int $height,
        Fit $fit = Fit::INSIDE,
        Scale $scale = Scale::ANY
    ): static
    {
        return $this->executeFunction('Resize', $width, $height, $fit, $scale);
    }

    public function rotate(float $angle, ?Color $background = null): static
    {
        return $this->executeFunction('Rotate', $angle, $background);
    }

    public function flip(Direction $direction = Direction::HORIZONTAL): static
    {
        return $this->executeFunction('Flip', $direction);
    }

    public function crop(int|X $x, int|Y $y, int $width, int $height): static
    {
        return $this->executeFunction('Crop', $x, $y, $width, $height);
    }

    public function resizeCanvas(
        int|X $x,
        int|Y $y,
        int $width,
        int $height,
        ?Color $background = null
    ): static
    {
        return $this->executeFunction('ResizeCanvas', $x, $y, $width, $height, $background);
    }

    public function opacify(int $opacity): static
    {
        return $this->executeFunction('Opacify', $opacity);
    }

    public function mask(ImageInterface $image): static
    {
        return $this->executeFunction('Mask', $image);
    }

    public function overlay(
        int|X $x,
        int|Y $y,
        string|ImageInterface $image
    ): static
    {
        return $this->executeFunction('Overlay', $x, $y, $image);
    }

    public function __call(string $name, array $arguments): mixed
    {
        $name = ucfirst($name);

        return $this->executeFunction($name, ...$arguments);
    }

    public function functions(string $function): FunctionInterface
    {
        $driverName = $this->getDriver()->getName();

        $file = __DIR__ . DS . 'Driver' . DS . $driverName . DS .
            'Functions' . DS . $function . 'Function.php';

        if (file_exists($file)) {
            $class = '\Pyncer\Image\Driver\\' . $driverName .
                '\Functions\\' . $function . 'Function';
            return new $class();
        }

        throw new InvalidArgumentException('Function is invalid. (' . $function . ')');
    }

    protected function executeFunction(string $function, mixed ...$arguments): mixed
    {
        $function = $this->functions($function);

        return $function->execute($this, ...$arguments);
    }
}
