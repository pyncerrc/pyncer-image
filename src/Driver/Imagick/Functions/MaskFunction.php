<?php
namespace Pyncer\Image\Driver\Imagick\Functions;

use Pyncer\Image\FunctionInterface;
use Pyncer\Image\ImageInterface;

class MaskFunction implements FunctionInterface
{
    public function execute(ImageInterface $image, mixed ...$arguments): mixed
    {
        $maskImage = $arguments[0];

        $canvas = $image->getDriver()->getImage()->new(
            $image->getWidth(),
            $image->getHeight(),
        );

        // Ensure mask image is same size as application image
        if ($maskImage->getWidth() !== $image->getWidth() ||
            $maskImage->getHeight() !== $image->getHeight()
        ) {
            $maskImage->resize($image->getWidth(), $image->getHeight());
        }

        // Make grayscale
        $maskImage->getHandle()->modulateImage(100, 0, 100);

        $width = $image->getWidth();
        $height = $image->getHeight();

        $iterator = $canvas->getHandle()->getPixelIterator();

        foreach ($iterator as $y => $pixels) {
            foreach ($pixels as $x => $pixel) {
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

                $pixel->setColor($imageColor->getHex());
            }

            $iterator->syncIterator();
        }

        // Replace current image handle with new masked handle
        $image->setHandle($canvas->getHandle());

        return $image;
    }
}
