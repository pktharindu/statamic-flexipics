<?php

namespace Pktharindu\FlexiPics\Enums;

use ArchTech\Enums\Comparable;

enum Mode: string
{
    use Comparable;

    case HTML = 'html';

    case JSON = 'json';

    case Array = 'array';
}
