<?php

declare(strict_types=1);

namespace Pktharindu\FlexiPics\ValueObjects;

use Assert\Assertion;
use Assert\AssertionFailedException;

final readonly class Size
{
    /**
     * @throws AssertionFailedException
     */
    public function __construct(public float $width, public float $height)
    {
        Assertion::greaterOrEqualThan($width, 0);
        Assertion::greaterOrEqualThan($height, 0);
    }
}
