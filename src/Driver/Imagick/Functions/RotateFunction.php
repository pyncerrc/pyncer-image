<?php
namespace Pyncer\Image\Driver\Imagick\Functions;

use Pyncer\Image\FunctionInterface;
use Pyncer\Image\ImageInterface;

class RotateFunction implements FunctionInterface
{
    public function execute(ImageInterface $image, mixed ...$arguments): mixed
    {
        $angle = max(-360, min(360, intval($arguments[0])));
        $background = $arguments[1];

        if ($angle !== 0) {
            $image->getHandle()->rotateImage($background->getHex(), $angle);
        }

        return $image;
    }
}
