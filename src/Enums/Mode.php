<?php

declare(strict_types=1);

namespace Pktharindu\FlexiPics\Enums;

use ArchTech\Enums\Comparable;

enum Mode: string
{
    use Comparable;

    case Html = 'html';

    case Json = 'json';

    case Array = 'array';
}
