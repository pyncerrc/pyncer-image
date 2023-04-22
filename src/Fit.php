<?php
namespace Pyncer\Image;

enum Fit: string
{
    case INSIDE = 'inside';
    case OUTSIDE = 'outside';
    case FILL = 'fill';
}
