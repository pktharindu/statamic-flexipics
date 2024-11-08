<?php

declare(strict_types=1);

namespace Pktharindu\FlexiPics\ValueObjects;

use Assert\Assertion;
use Assert\AssertionFailedException;

final readonly class Breakpoint
{
    /**
     * @throws AssertionFailedException
     */
    public function __construct(public string $handle, public ?string $size)
    {
        Assertion::notEmpty($handle);
    }

    public function equals(Breakpoint $breakpoint): bool
    {
        return $this->handle === $breakpoint->handle;
    }
}
