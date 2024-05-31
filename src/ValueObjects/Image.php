<?php

namespace Pktharindu\FlexiPics\ValueObjects;

use Assert\Assertion;
use Assert\AssertionFailedException;

final readonly class Image
{
    /**
     * @throws AssertionFailedException
     */
    public function __construct(
        public string $src,
        public string $class,
        public ?string $alt,
        public ?string $loading,
        public ?int $width,
        public ?int $height,
    ) {
        Assertion::notEmpty($src);
    }
}
