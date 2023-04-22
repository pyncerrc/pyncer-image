<?php
namespace Pyncer\Image\Driver\Imagick\Functions;

use Imagick;
use Pyncer\Image\FunctionInterface;
use Pyncer\Image\ImageInterface;

class Orientate implements FunctionInterface
{
    public function execute(ImageInterface $image, mixed ...$arguments): mixed
    {
        $imageHandle = $image->getHandle();

        $orientation = $imageHandle->getImageOrientation();

        if ($orientation !== Imagick::ORIENTATION_UNDEFINED &&
            $orientation !== Imagick::ORIENTATION_TOPLEFT
        ) {
            switch ($orientation) {
                case Imagick::ORIENTATION_TOPRIGHT:
                    $imageHandle->flopImage();
                    break;
                case Imagick::ORIENTATION_BOTTOMRIGHT:
                    $imageHandle->rotateImage('#000', 180);
                    break;
                case Imagick::ORIENTATION_BOTTOMLEFT:
                    $imageHandle->flopImage();
                    $imageHandle->rotateImage('#000', 180);
                    break;
                case Imagick::ORIENTATION_LEFTTOP:
                    $imageHandle->flopImage();
                    $imageHandle->rotateImage('#000', -90);
                    break;
                case Imagick::ORIENTATION_RIGHTTOP:
                    $imageHandle->rotateImage('#000', 90);
                    break;
                case Imagick::ORIENTATION_RIGHTBOTTOM:
                    $imageHandle->flopImage();
                    $imageHandle->rotateImage('#000', 90);
                    break;
                case Imagick::ORIENTATION_LEFTBOTTOM:
                    $imageHandle->rotateImage('#000', -90);
                    break;
            }

            $imageHandle->setImageOrientation(Imagick::ORIENTATION_TOPLEFT);
        }

        return $image;
    }
}
