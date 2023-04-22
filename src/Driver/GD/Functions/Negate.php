<?php
namespace Pyncer\Image\Driver\GD\Functions;

use Pyncer\Image\FunctionInterface;
use Pyncer\Image\ImageInterface;

use const IMG_FILTER_NEGATE;

class Negate implements FunctionInterface
{
    public function execute(ImageInterface $image, mixed ...$arguments): mixed
    {
        imagefilter($image->getHandle(), IMG_FILTER_NEGATE);

        return $image;
    }
}
