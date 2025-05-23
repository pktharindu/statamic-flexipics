<?php

declare(strict_types=1);

namespace Pktharindu\FlexiPics\Enums;

use ArchTech\Enums\Comparable;

enum Orientation: string
{
    use Comparable;

    case Landscape = 'landscape';

    case Portrait = 'portrait';
}
