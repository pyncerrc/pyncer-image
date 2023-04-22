<?php
namespace Pyncer\Image\Driver\GD\Functions;

use Pyncer\Image\FunctionInterface;
use Pyncer\Image\ImageInterface;

use const IMG_FILTER_GRAYSCALE;

class Grayscale implements FunctionInterface
{
    public function execute(ImageInterface $image, mixed ...$arguments): mixed
    {
        imagefilter($image->getHandle(), IMG_FILTER_GRAYSCALE);

        return $image;
    }
}
