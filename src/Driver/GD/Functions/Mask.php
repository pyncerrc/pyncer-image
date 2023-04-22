<?php
namespace Pyncer\Image\Driver\GD\Functions;

use Pyncer\Image\FunctionInterface;
use Pyncer\Image\ImageInterface;

use const IMG_FILTER_GRAYSCALE;

class Mask implements FunctionInterface
{
    public function execute(ImageInterface $image, mixed ...$arguments): mixed
    {
        $maskImage = $arguments[0];

        $canvas = $image->getDriver()->getImage()->new(
            $image->getWidth(),
            $image->getHeight()
        );

        imagealphablending($canvas->getHandle(), false);

        // Ensure mask image is same size as application image
        if ($maskImage->getWidth() !== $image->getWidth() ||
            $maskImage->getHeight() !== $image->getHeight()
        ) {
            $maskImage->resize($image->getWidth(), $image->getHeight());
        }

        imagefilter($maskImage->getHandle(), IMG_FILTER_GRAYSCALE);

        $width = $image->getWidth();
        $height = $image->getHeight();

        // Apply mask pixel by pixel
        for ($x = 0; $x < $width; ++$x) {
            for ($y = 0; $y < $height; ++$y) {

                $imageColor = $image->getPixel($x, $y);
                $maskColor = $maskImage->getPixel($x, $y);

                // Transparent is black
                if ($maskColor->getAlpha() === 0) {
                    $alpha = 0;
                } else {
                    // It doesn't matter what color channel we use since grayscale
                    $alpha = $maskColor->getRed();

                    // Preserve ratio of alpha of existing image
                    $alpha = round($imageColor->getAlpha() * $alpha / 255);
                }

                // Replace alpha
                $imageColor = $imageColor->withAlpha($alpha);

                $canvas->setPixel($x, $y, $imageColor);
            }
        }

        // Replace current image handle with new masked handle
        $image->setHandle($canvas->getHandle());

        return $image;
    }
}
