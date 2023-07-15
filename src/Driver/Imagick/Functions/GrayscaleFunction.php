<?php
namespace Pyncer\Image\Driver\Imagick\Functions;

use Pyncer\Image\FunctionInterface;
use Pyncer\Image\ImageInterface;

class GrayscaleFunction implements FunctionInterface
{
    public function execute(ImageInterface $image, mixed ...$arguments): mixed
    {
        $image->getHandle()->modulateImage(100, 0, 100);

        return $image;
    }
}
