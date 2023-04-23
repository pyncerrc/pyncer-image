<?php
namespace Pyncer\Image\Driver\GD\Functions;

use Pyncer\Image\Color;
use Pyncer\Image\FunctionInterface;
use Pyncer\Image\ImageInterface;

class GetPixelFunction implements FunctionInterface
{
    public function execute(ImageInterface $image, mixed ...$arguments): mixed
    {
        $x = $arguments[0];
        $y = $arguments[1];

        $index = imagecolorat($image->getHandle(), $x, $y);

        $color = imagecolorsforindex($image->getHandle(), $index);

        // Convert 127-0 to 0-255
        $color['alpha'] = round(255 * (127 - $color['alpha']) / 127);

        $color = array_values($color);

        return new Color($color);
    }
}
