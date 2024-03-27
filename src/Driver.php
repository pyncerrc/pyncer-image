<?php
namespace Pyncer\Image;

use Pyncer\Exception\InvalidArgumentException;
use Pyncer\Image\ImageInterface;
use Pyncer\Utility\AbstractDriver;
use Stringable;

final class Driver extends AbstractDriver
{
    public function __construct(
        string $name,
        ?int $quality = null,
        ?int $speed = null,
        array $params = []
    ) {
        parent::__construct($name, $params);

        $this->setQuality($quality);
        $this->setSpeed($speed);
    }

    protected function getType(): string
    {
        return 'image';
    }

    protected function getClass(): string
    {
        return '\Pyncer\Image\Driver\\' . $this->getName() . '\\Image';
    }

    /**
    * @return \Pyncer\Image\ImageInterface
    */
    public function getImage(): ImageInterface
    {
        $class = $this->getClass();
        return new $class($this);
    }

    public function getQuality(): ?int
    {
        return $this->getInt('quality', null);
    }
    public function setQuality(?int $value): static
    {
        return $this->setInt('quality', $value);
    }

    public function getSpeed(): ?int
    {
        return $this->getInt('speed', null);
    }
    public function setSpeed(?int $value): static
    {
        return $this->setInt('speed', $value);
    }

    public function set(string $key, mixed $value): static
    {
        switch ($key) {
            case 'speed':
            case 'quality':
                if ($value !== null && !is_int($value)) {
                    throw new InvalidArgumentException('The ' . $key . ' param must be an integer or null.');
                }
                break;
        }

        return parent::set($key, $value);
    }
}
