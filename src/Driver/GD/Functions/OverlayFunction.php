<?php
namespace Pyncer\Image\Driver\GD\Functions;

use Pyncer\Image\FunctionInterface;
use Pyncer\Image\ImageInterface;
use Pyncer\Image\X;
use Pyncer\Image\Y;

class OverlayFunction implements FunctionInterface
{
    public function execute(ImageInterface $image, mixed ...$arguments): mixed
    {
        $x = $arguments[0];
        $y = $arguments[1];
        $overlayImage = $arguments[2];

        if (is_string($overlayImage)) {
            $overlayImage = $image->getDriver()->getImage()->open($overlayImage);
        }

        switch ($x) {
            case X::LEFT;
                $x = 0;
                break;
            case X::RIGHT:
                $x = $image->getWidth() - $overlayImage->getWidth();
                break;
            case X::CENTER:
                $x = round(($image->getWidth() - $overlayImage->getWidth()) / 2);
                break;
            default:
                $x = intval($x);
        }

        switch ($y) {
            case Y::TOP;
                $y = 0;
                break;
            case Y::BOTTOM:
                $y = $image->getHeight() - $overlayImage->getHeight();
                break;
            case Y::CENTER:
                $y = round(($image->getHeight() - $overlayImage->getHeight()) / 2);
                break;
            default:
                $y = intval($y);
        }

        imagealphablending($image->getHandle(), true);
        imagecopy(
            $image->getHandle(),
            $overlayImage->getHandle(),
            $x, $y, 0, 0,
            $overlayImage->getWidth(),
            $overlayImage->getHeight()
        );

        return $image;
    }
}
