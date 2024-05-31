<?php

namespace Pktharindu\FlexiPics\Enums;

use ArchTech\Enums\Comparable;

enum Orientation: string
{
    use Comparable;

    case LANDSCAPE = 'landscape';

    case PORTRAIT = 'portrait';
}
