<?php
namespace Pyncer\Image;

use Pyncer\Image\ImageInterface;

interface FunctionInterface
{
    public function execute(ImageInterface $image, mixed ...$arguments): mixed;
}
