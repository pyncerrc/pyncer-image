<?php
namespace Pyncer\Image\Driver\Imagick\Functions;

use Pyncer\Image\Color;
use Pyncer\Image\FunctionInterface;
use Pyncer\Image\ImageInterface;

class GetPixelFunction implements FunctionInterface
{
    public function execute(ImageInterface $image, mixed ...$arguments): mixed
    {
        $x = $arguments[0];
        $y = $arguments[1];

        $pixel = $image->getHandle()->getImagePixelColor($x, $y);

        $color = $pixel->getColor(2);
        $color = array_values($color);

        return new Color($color);
    }
}
