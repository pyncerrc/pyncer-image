<?php
namespace Pyncer\Image\Driver\GD\Functions;

use Pyncer\Image\FunctionInterface;
use Pyncer\Image\ImageInterface;

class SetPixelFunction implements FunctionInterface
{
    public function execute(ImageInterface $image, mixed ...$arguments): mixed
    {
        $x = $arguments[0];
        $y = $arguments[1];
        $color = $arguments[2];
        $color = $color->withBitrate(32);

        $alpha = round(127 * (255 - $color->getAlpha()) / 255);

        $color = imagecolorallocatealpha(
            $image->getHandle(),
            $color->getRed(),
            $color->getGreen(),
            $color->getBlue(),
            $alpha
        );

        imagesetpixel($image->getHandle(), $x, $y, $color);

        return $image;
    }
}
