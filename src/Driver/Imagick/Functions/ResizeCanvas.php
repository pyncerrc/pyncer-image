<?php
namespace Pyncer\Image\Driver\Imagick\Functions;

use Imagick;
use Pyncer\Image\FunctionInterface;
use Pyncer\Image\ImageInterface;
use Pyncer\Image\X;
use Pyncer\Image\Y;

class ResizeCanvas implements FunctionInterface
{
    public function execute(ImageInterface $image, mixed ...$arguments): mixed
    {
        // $point = [0, 0];
        $size = $image->getSize();

        $left = $arguments[0];
        $top = $arguments[1];
        $width = $arguments[2];
        $height = $arguments[3];
        $background = $arguments[4];

        switch ($left) {
            case X::LEFT;
                $left = 0;
                break;
            case X::RIGHT:
                $left = $width - $size[0];
                break;
            case X::CENTER:
                $left = round(($width - $size[0]) / 2);
                break;
            default:
                $left = intval($left);
        }

        switch ($top) {
            case Y::TOP;
                $top = 0;
                break;
            case Y::BOTTOM:
                $top = $height - $size[1];
                break;
            case Y::CENTER:
                $top = round(($height - $size[1]) / 2);
                break;
            default:
                $top = intval($top);
        }

        $canvas = $image->getDriver()->getImage()->new(
            $width,
            $height,
            $background,
        );

        $imageHandle = $image->getHandle();
        $canvasHandle = $canvas->getHandle();

        if ($canvasHandle->getImageColorspace() !== $imageHandle->getImageColorspace()) {
            $canvasHandle->transformImageColorspace($imageHandle->getImageColorspace());
        }

        // Clone to preserve alpha channel
        $opacityHandle = clone $image->getHandle();

        // Set alpha channel to opaque so that alpha blending doesn't occur
        $image->getHandle()->setImageAlphaChannel(Imagick::ALPHACHANNEL_OPAQUE);
        $canvasHandle->compositeImage(
            $image->getHandle(),
            Imagick::COMPOSITE_REPLACE,
            $left,
            $top,
        );

        // Restore the original alpha channel
        $canvasHandle->compositeImage(
            $opacityHandle,
            Imagick::COMPOSITE_COPYOPACITY,
            $left,
            $top,
        );

        $opacityHandle->destroy();

        $image->setHandle($canvasHandle);

        return $image;
    }
}
