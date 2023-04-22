<?php
namespace Pyncer\Image;

use Pyncer\Exception\InvalidArgumentException;
use Pyncer\Image\Exception\DriverNotFoundException;
use Pyncer\Image\ImageInterface;

use const DIRECTORY_SEPARATOR as DS;

final class Driver
{
    private string $name;
    private ?int $quality = null;
    private ?int $speed = null;
    private array $params = [];

    public function __construct(
        string $name = '',
        array $params = []
    ) {
        $this->setData($params);
        $this->setName($name);
    }

    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $value): static
    {
        if (!preg_match('/\A[A-Za-z0-9_]+\z/', $value)) {
            throw new InvalidArgumentException(
                'The specified driver name, ' . $value . ', is invalid.'
            );
        }

        $file = __DIR__ . DS . 'Driver' . DS . $value . DS . 'Image.php';
        if (!file_exists($file)) {
            throw new DriverNotFoundException($file);
        }

        $this->name = $value;

        return $this;
    }

    /**
    * @return \Pyncer\Image\ImageInterface
    */
    public function getImage(): ImageInterface
    {
        $class = '\Pyncer\Image\Driver\\' . $this->getName() . '\\Image';
        return new $class($this);
    }

    public function getQuality(): ?int
    {
        return $this->quality;
    }
    public function setQuality(?int $value): static
    {
        $this->quality = $value;
        return $this;
    }

    public function getSpeed(): ?int
    {
        return $this->speed;
    }
    public function setSpeed(?int $value): static
    {
        $this->speed = $value;
        return $this;
    }

    public function getParam(string $param, mixed $default = null): mixed
    {
        switch ($param) {
            case 'name':
                return $this->getName();
            case 'quality':
                return $this->getQuality();
            case 'speed':
                return $this->getSpeed();
        }

        return $this->params[$param] ?? $default;
    }
    public function setParam(string $param, mixed $value): static
    {
        switch ($param) {
            case 'name':
                return $this->setName($value);
            case 'quality':
                return $this->setQuality($value);
            case 'speed':
                return $this->setSpeed($value);
        }

        if ($value === null) {
            unset($this->params[$param]);
        } else {
            $this->params[$param] = $value;
        }

        return $this;
    }

    public function getData(): array
    {
        $data = [
            'name' => $this->getName(),
            'quality' => $this->getQuality(),
            'speed' => $this->getSpeed(),
        ];

        foreach ($this->params as $key => $value) {
            $data[$key] = $value;
        }

        return $data;
    }
    public function setData(array $data): static
    {
        foreach ($data as $key => $value) {
            $this->setParam($key, $value);
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getName();
    }
}
