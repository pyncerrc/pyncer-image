<?php
namespace Pyncer\Image\Driver\GD\Functions;

use Pyncer\Image\FunctionInterface;
use Pyncer\Image\ImageInterface;
use Pyncer\Image\Direction;

use const IMG_FLIP_HORIZONTAL;
use const IMG_FLIP_VERTICAL;

class Flip implements FunctionInterface
{
    public function execute(ImageInterface $image, mixed ...$arguments): mixed
    {
        $direction = $arguments[0];

        if ($direction === Direction::VERTICAL) {
            imageflip($image->getHandle(), IMG_FLIP_VERTICAL);
        } else {
            imageflip($image->getHandle(), IMG_FLIP_HORIZONTAL);
        }

        return $image;
    }
}
