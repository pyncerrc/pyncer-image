<?php
namespace Pyncer\Image\Driver\Imagick\Functions;

use Imagick;
use Pyncer\Image\FunctionInterface;
use Pyncer\Image\ImageInterface;

/**
 * Strip exif data without removing icc profile
 */
class Strip implements FunctionInterface
{
    public function execute(ImageInterface $image, mixed ...$arguments): mixed
    {
        $imageHandle = $image->getHandle();

        $profiles = $imageHandle->getImageProfiles('icc', true);

        $imageHandle->stripImage();

        if ($profiles) {
            $imageHandle->profileImage('icc', $profiles['icc']);
        }

        return $image;
    }
}
