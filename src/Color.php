<?php
namespace Pyncer\Image;

use Pyncer\Exception\InvalidArgumentException;

class Color
{
    private int $red;
    private int $green;
    private int $blue;
    private int $alpha;
    private int $bitrate;

    public function __construct(
        null|int|string|array $color = null,
        int $bitrate = 32
    ) {
        $this->setColor($color, $bitrate);
    }

    protected function setColor(
        null|int|string|array $value,
        int $bitrate = 32
    ): static
    {
        if (is_string($value)) {
            $this->setColorFromString($value, $bitrate);
        } elseif (is_int($value)) {
            $this->setColorFromInt($value, $bitrate);
        } elseif (is_array($value)) {
            $this->setColorFromArray($value, $bitrate);
        } else {
            $this->setColorFromArray([0, 0, 0, 0], $bitrate);
        }

        return $this;
    }

    public function getBitrate(): int
    {
        return $this->bitrate;
    }
    protected function setBitrate(int $value): static
    {
        if (!$this->isValidBitrate($value)) {
            throw new InvalidArgumentException('Bitrate is invalid. (' . $value . ')');
        }

        $this->bitrate = $value;
        return $this;
    }
    public function withBitrate(int $value): static
    {
        $color = clone $this;
        $color->setBitrate($value);

        if ($this->getBitrate() === $color->getBitrate()) {
            return $color;
        }

        if ($this->getBitrate() === 64) {
            $color->setRed(round($color->getRed() / 65535 * 255));
            $color->setBlue(round($color->getBlue() / 65535 * 255));
            $color->setGreen(round($color->getGreen() / 65535 * 255));
            $color->setAlpha(round($color->getAlpha() / 65535 * 255));
        } elseif ($color->getBitrate() === 64) {
            $color->setRed(round($color->getRed() / 255 * 65535));
            $color->setBlue(round($color->getBlue() / 255 * 65535));
            $color->setGreen(round($color->getGreen() / 255 * 65535));
            $color->setAlpha(round($color->getAlpha() / 255 * 65535));
        }

        return $color;
    }

    public function getRed(): int
    {
        return $this->red;
    }
    protected function setRed(int $value): static
    {
        $this->red = $value;
        return $this;
    }
    public function withRed(int $value): static
    {
        return new static(
            [$value, $this->getGreen(), $this->getBlue(), $this->getAlpha()],
            $this->getBitrate()
        );
    }

    public function getGreen(): int
    {
        return $this->green;
    }
    protected function setGreen(int $value): static
    {
        $this->green = $value;
        return $this;
    }
    public function withGreen(int $value): static
    {
        return new static(
            [$this->getRed(), $value, $this->getBlue(), $this->getAlpha()],
            $this->getBitrate()
        );
    }

    public function getBlue(): int
    {
        return $this->blue;
    }
    protected function setBlue(int $value): static
    {
        $this->blue = $value;
        return $this;
    }
    public function withBlue(int $value): static
    {
        return new static(
            [$this->getRed(), $this->getGreen(), $value, $this->getAlpha()],
            $this->getBitrate()
        );
    }

    public function getAlpha(): int
    {
        return $this->alpha;
    }
    protected function setAlpha(int $value): static
    {
        $this->alpha = $value;
        return $this;
    }
    public function withAlpha(int $value): static
    {
        return new static(
            [$this->getRed(), $this->getGreen(), $this->getBlue(), $value],
            $this->getBitrate()
        );
    }

    public function getInt(): int
    {
        switch ($this->getBitrate()) {
            case '24':
                return ($this->getRed() << 16) + ($this->getGreen() << 8) + $this->getBlue();
            case '32':
                return ($this->getAlpha() << 24) + ($this->getRed() << 16) + ($this->getGreen() << 8) + $this->getBlue();
            case '64':
                return ($this->getAlpha() << 48) + ($this->getRed() << 32) + ($this->getGreen() << 16) + $this->getBlue();
        }
    }
    public function getHex(): string
    {
        switch ($this->getBitrate()) {
            case '24':
                return sprintf('#%02x%02x%02x', $this->getRed(), $this->getGreen(), $this->getBlue());
            case '32':
                return sprintf('#%02x%02x%02x%02x', $this->getRed(), $this->getGreen(), $this->getBlue(), $this->getAlpha());
            case '64':
                return sprintf('#%04x%04x%04x%04x', $this->getRed(), $this->getGreen(), $this->getBlue(), $this->getAlpha());
        }
    }

    private function setColorFromString(string $value, int $bitrate): static
    {
        $color = [];

        if (preg_match('/^#?[0-9a-f]{8}$/i', $value)) {
            $value = ltrim($value, '#');

            $color = [
                hexdec(substr($value, 0, 2)),
                hexdec(substr($value, 2, 2)),
                hexdec(substr($value, 4, 2)),
                hexdec(substr($value, 6, 2))
            ];
        } else if (preg_match('/^#?[0-9a-f]{6}$/i', $value)) {
            $value = ltrim($value, '#');

            $color = [
                hexdec(substr($value, 0, 2)),
                hexdec(substr($value, 2, 2)),
                hexdec(substr($value, 4, 2)),
                255
            ];
        } else if (preg_match('/^#?[0-9a-f]{4}$/i', $value)) {
            $value = ltrim($value, '#');

            $color = [
                hexdec(substr($value, 0, 1) . substr($value, 0, 1)),
                hexdec(substr($value, 1, 1) . substr($value, 1, 1)),
                hexdec(substr($value, 2, 1) . substr($value, 2, 1)),
                hexdec(substr($value, 3, 1) . substr($value, 3, 1))
            ];

        } else if (preg_match('/^#?[0-9a-f]{3}$/i', $value)) {
            $value = ltrim($value, '#');

            $color = [
                hexdec(substr($value, 0, 1) . substr($value, 0, 1)),
                hexdec(substr($value, 1, 1) . substr($value, 1, 1)),
                hexdec(substr($value, 2, 1) . substr($value, 2, 1)),
                255
            ];
        }

        if ($color) { // Only set if previously handled
            if ($bitrate == 64) {
                $color[0] = round($color[0] / 255 * 65535);
                $color[1] = round($color[1] / 255 * 65535);
                $color[2] = round($color[2] / 255 * 65535);
                $color[3] = round($color[3] / 255 * 65535);
            }

            return $this->setColorFromArray($color, $bitrate);
        }

        $color = explode(',', $value);
        $color = array_map('intval', $color);

        return $this->setColorFromArray($color, $bitrate);
    }

    private function setColorFromInt(int $value, int $bitrate): static
    {
        $this->setBitrate($bitrate);

        switch ($this->getBitrate()) {
            case '24':
                $this->setAlpha(0);
                $this->setRed(($value >> 16) & 0xFF);
                $this->setGreen(($value >> 8) & 0xFF);
                $this->setBlue($value & 0xFF);
                break;
            case '32':
                $this->setAlpha(($value >> 24) & 0xFF);
                $this->setRed(($value >> 16) & 0xFF);
                $this->setGreen(($value >> 8) & 0xFF);
                $this->setBlue($value & 0xFF);
                break;
            case '64':
                $this->setAlpha(($value >> 48) & 0xFFFF);
                $this->setRed(($value >> 32) & 0xFFFF);
                $this->setGreen(($value >> 16) & 0xFFFF);
                $this->setBlue($value & 0xFFFF);
                break;
        }

        return $this;
    }
    private function setColorFromArray(array $value, int $bitrate): static
    {
        if (count($value) === 3) {
            $value[] = 255;
        } elseif (count($value) !== 4) {
            throw new InvalidArgumentException('Color array is invalid.');
        }

        $this->setBitrate($bitrate);

        $max = $this->getMaxColorSize($this->getBitrate());

        if ($value[0] > $max ||
            $value[1] > $max ||
            $value[2] > $max ||
            $value[3] > $max ||
            $value[0] < 0 ||
            $value[1] < 0 ||
            $value[2] < 0 ||
            $value[3] < 0
        ) {
            throw new InvalidArgumentException('Color is invalid.');
        }

        $this->setRed($value[0]);
        $this->setGreen($value[1]);
        $this->setBlue($value[2]);
        $this->setAlpha($value[3]);

        return $this;
    }

    private function isValidBitrate($value): bool
    {
        return in_array($value, [24, 32, 64]);
    }

    private function getMaxColorSize(int $bitrate): int
    {
        if ($bitrate == 64) {
            return 65535;
        }

        return 255;
    }
}
