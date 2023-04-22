<?php
namespace Pyncer\Image\Driver\Imagick\Functions;

use Pyncer\Image\FunctionInterface;
use Pyncer\Image\ImageInterface;

class SetPixel implements FunctionInterface
{
    public function execute(ImageInterface $image, mixed ...$arguments): mixed
    {
        $x = $arguments[0];
        $y = $arguments[1];
        $color = $arguments[2];

        $pixel = $image->getHandle()->getImagePixelColor($x, $y);

        $pixel->setColor($color->getHex());

        /* $iterator = $image->getHandle()->getPixelIterator();

        $iterator->setIteratorRow($y);
        $row = $iterator->getCurrentIteratorRow();
        $pixel = $row[$x];

        $pixel->setColor($color->getHex());

        $iterator->syncIterator(); */

        return $image;
    }
}
