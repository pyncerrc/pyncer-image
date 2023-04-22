<?php
namespace Pyncer\Image\Driver\Imagick\Functions;

use Pyncer\Image\Color;
use Pyncer\Image\FunctionInterface;
use Pyncer\Image\ImageInterface;

class Opacify implements FunctionInterface
{
    public function execute(ImageInterface $image, mixed ...$arguments): mixed
    {
        $opacity = max(0, min(100, intval($arguments[0])));

        $color = round(255 * $opacity / 100);

        $maskColor = new Color([$color, $color, $color]);

        $maskImage = $image->getDriver()->getImage()->new(
            $image->getWidth(),
            $image->getHeight(),
            $maskColor
        );

        $image->mask($maskImage);

        return $image;
    }
}
