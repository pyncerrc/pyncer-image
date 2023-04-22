<?php
namespace Pyncer\Image\Driver\Imagick\Functions;

use Imagick;
use Pyncer\Image\Fit;
use Pyncer\Image\FunctionInterface;
use Pyncer\Image\ImageInterface;
use Pyncer\Image\Scale;

class Resize implements FunctionInterface
{
    public function execute(ImageInterface $image, mixed ...$arguments): mixed
    {
        $size = $image->getSize();

        $width = $arguments[0];
        $height = $arguments[1];
        $fit = $arguments[2];
        $scale = $arguments[3];

        $filter = $image->getDriver()->getParam(
            'imagick_resize_filter',
            Imagick::FILTER_LANCZOS
        );

        $blur = $image->getDriver()->getParam(
            'imagick_resize_blur',
            1
        );

        if ($fit == Fit::INSIDE || $fit == Fit::OUTSIDE) {
            $rx = $size[0] / $width;
            $ry = $size[1] / $height;

            if ($fit == Fit::INSIDE) {
                $ratio = ($rx > $ry ? $rx : $ry);
            } else {
                $ratio = ($rx < $ry ? $rx : $ry);
            }

            $width = round($size[0] / $ratio);
            $height = round($size[1] / $ratio);
        }

        if (($scale === SCALE::DOWN && $width >= $size[0] && $height >= $size[1]) ||
            ($scale === SCALE::UP && $width <= $size[0] && $height <= $size[1])
        ) {
            $width = $size[0];
            $height = $size[1];
        }

        $image->getHandle()->resizeImage(
            $width,
            $height,
            $filter,
            $blur,
        );

        return $image;
    }
}
