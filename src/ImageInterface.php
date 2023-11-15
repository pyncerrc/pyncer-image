<?php
namespace Pyncer\Image;

use Pyncer\Image\Color;
use Pyncer\Image\Driver;
use Pyncer\Image\Direction;
use Pyncer\Image\Fit;
use Pyncer\Image\FunctionInterface;
use Pyncer\Image\ImageFormat;
use Pyncer\Image\Scale;
use Pyncer\Image\X;
use Pyncer\Image\Y;

interface ImageInterface
{
    public function getDriver(): Driver;
    public function getImage(): ImageInterface;

    public function getHandle(): ?object;
    public function setHandle(?object $value): static;

    public function open(string $file): static;
    public function new(
        int $width,
        int $height,
        ?Color $background = null,
    ): static;
    public function save(?string $file = null): static;
    public function close(): static;

    public function getImageFormat(): ImageFormat;
    public function setImageFormat(ImageFormat $value): static;

    public function getMimeType(): string;

    public function getQuality(): int;
    public function setQuality(int $value): static;

    public function getSpeed(): int;
    public function setSpeed(int $value): static;

    public function getSize(): array;
    public function setSize(int $width, int $height): static;

    public function getWidth(): int;
    public function setWidth(int $value): static;

    public function getHeight(): int;
    public function setHeight(int $value): static;

    public function getPixel(int $x, int $y): Color;
    public function setPixel(int $x, int $y, Color $color): static;

    public function resize(
        int $width,
        int $height,
        Fit $fit = Fit::INSIDE,
        Scale $scale = Scale::ANY
    ): static;

    public function rotate(float $angle, ?Color $background = null): static;

    public function flip(Direction $direction = Direction::HORIZONTAL): static;

    public function crop(int|X $x, int|Y $y, int $width, int $height): static;

    public function resizeCanvas(
        int|X $x,
        int|Y $y,
        int $width,
        int $height,
        ?Color $background = null
    ): static;

    public function opacify(int $opacity): static;

    public function mask(ImageInterface $image): static;

    public function overlay(
        int|X $x,
        int|Y $y,
        string|ImageInterface $image
    ): static;

    public function functions(string $function): FunctionInterface;
}
