<?php
namespace Pyncer\Image\Driver\Imagick\Functions;

use Imagick;
use Pyncer\Image\FunctionInterface;
use Pyncer\Image\ImageInterface;

class Negate implements FunctionInterface
{
    public function execute(ImageInterface $image, mixed ...$arguments): mixed
    {
        $image->getHandle()->negateImage(false, Imagick::CHANNEL_DEFAULT);

        return $image;
    }
}
