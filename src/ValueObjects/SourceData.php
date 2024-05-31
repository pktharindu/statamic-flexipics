<?php

namespace Pktharindu\FlexiPics\ValueObjects;

use Assert\Assertion;
use Assert\AssertionFailedException;

final readonly class SourceData
{
    /**
     * @param  Size[]  $srcset
     *
     * @throws AssertionFailedException
     */
    public function __construct(public array $srcset, public ?string $sizes)
    {
        Assertion::notEmpty($srcset);
        Assertion::allIsInstanceOf($srcset, Size::class);
    }
}
