<?php
namespace Pyncer\Image\Exception;

use Pyncer\Image\Exception\Exception;
use Pyncer\Exception\FileNotFoundException;

class ImageFileNotFoundException extends FileNotFoundException implements
    Exception
{}
