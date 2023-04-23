<?php
namespace Pyncer\Image\Driver\GD\Functions;

use Pyncer\Image\FunctionInterface;
use Pyncer\Image\ImageInterface;

class ResizeFunction implements FunctionInterface
{
    public function execute(ImageInterface $image, mixed ...$arguments): mixed
    {
        $handle = $image->getHandle();
        $size = $image->getSize();

        $width = $arguments[0];
        $height = $arguments[1];
        $fit = $arguments[2];
        $scale = $arguments[3];

        if ($fit == 'inside' || $fit == 'outside') {
            $rx = $size[0] / $width;
            $ry = $size[1] / $height;

            if ($fit == 'inside') {
                $ratio = ($rx > $ry ? $rx : $ry);
            } else {
                $ratio = ($rx < $ry ? $rx : $ry);
            }

            $width = round($size[0] / $ratio);
            $height = round($size[1] / $ratio);
        }

        if (($scale === 'down' && $width >= $size[0] && $height >= $size[1]) ||
            ($scale === 'up' && $width <= $size[0] && $height <= $size[1])
        ) {
            $width = $size[0];
            $height = $size[1];
        }

        $newHandle = imagecreatetruecolor($width, $height);

        // Preserve alpha
        $alphaColorIndex = imagecolortransparent($handle);
        if ($alphaColorIndex != -1) {
            $rgba = imagecolorsforindex($newHandle, $alphaColorIndex);
            $alphaColor = imagecolorallocatealpha(
                $newHandle,
                $rgba['red'],
                $rgba['green'],
                $rgba['blue'],
                127
            );
            imagefill($newHandle, 0, 0, $alphaColor);
            imagecolortransparent($newHandle, $alphaColor);
        } else {
            imagealphablending($newHandle, false);
            imagesavealpha($newHandle, true);
        }

        imagecopyresampled(
            $newHandle,
            $handle,
            0,
            0,
            0,
            0,
            $width,
            $height,
            $size[0],
            $size[1]
        );

        $image->setHandle($newHandle);

        return $image;
    }
}
