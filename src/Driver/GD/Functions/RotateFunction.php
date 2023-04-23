<?php
namespace Pyncer\Image\Driver\GD\Functions;

use Pyncer\Image\FunctionInterface;
use Pyncer\Image\ImageInterface;

class RotateFunction implements FunctionInterface
{
    public function execute(ImageInterface $image, mixed ...$arguments): mixed
    {
        $angle = max(-360, min(360, intval($arguments[0]))) * -1;
        if ($angle < 0) {
		    $angle = 360 + $angle;
        }

        $background = $arguments[1];

        if ($angle !== 0) {
            $imageHandle = $image->getHandle();
            imagealphablending($imageHandle, false);
            imagesavealpha($imageHandle, true);

            $backgroundColor = imagecolorallocatealpha(
                $imageHandle,
                $background->getRed(),
                $background->getGreen(),
                $background->getBlue(),
                127 - floor($background->getAlpha() / 2)
            );

            $imageHandle = imagerotate($imageHandle, $angle, $backgroundColor);
            $image->setHandle($imageHandle);
        }

        return $image;
    }
}
