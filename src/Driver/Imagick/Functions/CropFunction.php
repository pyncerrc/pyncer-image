<?php
namespace Pyncer\Image\Driver\Imagick\Functions;

use Pyncer\Exception\InvalidArgumentException;
use Pyncer\Image\FunctionInterface;
use Pyncer\Image\ImageInterface;
use Pyncer\Image\X;
use Pyncer\Image\Y;

class CropFunction implements FunctionInterface
{
    public function execute(ImageInterface $image, mixed ...$arguments): mixed
    {
        $size = $image->getSize();

        $left = $arguments[0];
        $top = $arguments[1];
        $width = $arguments[2];
        $height = $arguments[3];

        switch ($left) {
            case X::LEFT;
                $left = 0;
                break;
            case X::RIGHT:
                $left = $size[0] - $width;
                break;
            case X::CENTER:
                $left = round(($size[0] - $width) / 2);
                break;
            default:
                $left = intval($left);
        }

        if ($left < 0) {
            $width = $left + $width;
            $left = 0;
        }

        if ($width > ($size[0] - $left)) {
            $width = $size[0] - $left;
        }

        switch ($top) {
            case Y::TOP;
                $top = 0;
                break;
            case Y::BOTTOM:
                $top = $size[1] - $height;
                break;
            case Y::CENTER:
                $top = round(($size[1] - $height) / 2);
                break;
            default:
                $top = intval($top);
        }

        if ($top < 0) {
            $height = $top + $height;
            $top = 0;
        }

        if ($height > ($size[1] - $top)) {
            $height = $size[1] - $top;
        }

        if ($width <= 0 || $height <= 0) {
            throw new InvalidArgumentException('Crop dimensions are invalid.');
        }

        $image->getHandle()->cropImage($width, $height, $left, $top);

        return $image;
    }
}
