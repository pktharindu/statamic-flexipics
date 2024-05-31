<?php

namespace Pktharindu\FlexiPics\ValueObjects;

use Assert\Assertion;
use Assert\AssertionFailedException;

final readonly class Source
{
    /**
     * @throws AssertionFailedException
     */
    public function __construct(
        public string $type,
        public string $srcset,
        public ?string $media,
        public ?string $sizes,
    ) {
        Assertion::notEmpty($type);
        Assertion::notEmpty($srcset);
    }

    public function equals(Source $source): bool
    {
        return $this->type === $source->type
            && $this->srcset === $source->srcset
            && $this->media === $source->media
            && $this->sizes === $source->sizes;
    }
}
