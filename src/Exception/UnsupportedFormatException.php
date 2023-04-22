<?php
namespace Pyncer\Image\Exception;

use Pyncer\Image\Exception\Exception;
use Pyncer\Exception\RuntimeException;
use Throwable;

class UnsupportedFormatException extends RuntimeException implements
    Exception
{
    protected string $format;

    public function __construct(
        string $format,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        $this->format = $format;

        parent::__construct(
            'The specified image format, ' . $file . ', was not found.',
            $code,
            $previous
        );
    }

    public function getFormat(): string
    {
        return $this->format;
    }
}
