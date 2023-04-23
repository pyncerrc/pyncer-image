<?php
namespace Pyncer\Image\Driver\Imagick\Functions;

use Pyncer\Image\Direction;
use Pyncer\Image\FunctionInterface;
use Pyncer\Image\ImageInterface;

class FlipFunction implements FunctionInterface
{
    public function execute(ImageInterface $image, mixed ...$arguments): mixed
    {
        $direction = $arguments[0];

        if ($direction === Direction::VERTICAL) {
            $image->getHandle()->flipImage();
        } else {
            $image->getHandle()->flopImage();
        }

        return $image;
    }
}
