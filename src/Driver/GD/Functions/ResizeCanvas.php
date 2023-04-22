<?php
namespace Pyncer\Image\Driver\GD\Functions;

use Pyncer\Image\FunctionInterface;
use Pyncer\Image\ImageInterface;
use Pyncer\Image\X;
use Pyncer\Image\Y;

class ResizeCanvas implements FunctionInterface
{
    public function execute(ImageInterface $image, mixed ...$arguments): mixed
    {
        $handle = $image->getHandle();
        $point = [0, 0];
        $size = $image->getSize();

        $left = $arguments[0];
        $top = $arguments[1];
        $width = $arguments[2];
        $height = $arguments[3];
        $background = $arguments[4];

        switch ($left) {
            case X::LEFT;
                $left = 0;
                break;
            case X::RIGHT:
                $left = $width - $size[0];
                break;
            case X::CENTER:
                $left = round(($width - $size[0]) / 2);
                break;
            default:
                $left = intval($left);
        }

        if ($left < 0) {
            $left = 0;
            $point[0] = abs($left);
            $size[0] = $left + $width;
        }

        if ($width <= $size[0]) {
            $size[0] = $width;
        }

        switch ($top) {
            case Y::TOP;
                $top = 0;
                break;
            case Y::BOTTOM:
                $top = $height - $size[1];
                break;
            case Y::CENTER:
                $top = round(($height - $size[1]) / 2);
                break;
            default:
                $top = intval($top);
        }

        if ($top < 0) {
            $top = 0;
            $point[1] = abs($top);
            $size[1] = $top + $height;
        }

        if ($height <= $size[1]) {
            $size[1] = $height;
        }

        $newHandle = imagecreatetruecolor($width, $height);

        $backgroundColor = imagecolorallocatealpha(
            $newHandle,
            $background->getRed(),
            $background->getGreen(),
            $background->getBlue(),
            127 - floor($background->getAlpha() / 2)
        );

        imagefill($newHandle, 0, 0, $backgroundColor);

        // Preserve alpha of source image
        $alphaColor = imagecolorallocatealpha($newHandle, 255, 255, 255, 127);
        imagealphablending($newHandle, false);
        imagefilledrectangle($newHandle, $left, $top, $left + $size[0] - 1, $top + $size[1] - 1, $alphaColor);

        imagecopy($newHandle, $handle, $left, $top, $point[0], $point[1], $size[0], $size[1]);

        $image->setHandle($newHandle);

        return $image;
    }
}
