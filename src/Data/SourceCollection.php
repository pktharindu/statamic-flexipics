<?php

namespace Pktharindu\FlexiPics\Data;

use Assert\Assertion;
use Illuminate\Support\Collection;
use Pktharindu\FlexiPics\ValueObjects\Source;

/**
 * @extends Collection<int, Source>
 */
class SourceCollection extends Collection
{
    /**
     * @param  Source[]  $items
     */
    public function __construct(array $items = [])
    {
        Assertion::allIsInstanceOf($items, Source::class);
        parent::__construct($items);
    }

    public function addSource(Source $source): self
    {
        $this->items = collect($this->items)
            ->reject->equals($source)
            ->values()
            ->all();

        return $this->add($source);
    }
}
