<?php

declare(strict_types=1);

namespace Pktharindu\FlexiPics\Data;

use Assert\Assertion;
use Illuminate\Support\Collection;
use Pktharindu\FlexiPics\ValueObjects\Breakpoint;

/**
 * @extends Collection<int, Breakpoint>
 */
class BreakpointCollection extends Collection
{
    /**
     * @param  Breakpoint[]  $items
     */
    public function __construct(array $items = [])
    {
        Assertion::allIsInstanceOf($items, Breakpoint::class);
        parent::__construct($items);
    }

    public function addBreakpoint(Breakpoint $breakpoint): self
    {
        $this->items = collect($this->items)
            ->reject->equals($breakpoint)
            ->values()
            ->all();

        return $this->add($breakpoint);
    }

    public function default(): ?string
    {
        return $this->firstWhere('handle', 'default')?->size;
    }

    public function getByHandle(string $handle, ?string $default = null): ?string
    {
        return $this->firstWhere('handle', $handle)->size ?? $default;
    }

    /**
     * @param  string|string[]  $handle
     */
    public function hasAnyHandle(string|array $handle): bool
    {
        $handles = is_array($handle) ? $handle : func_get_args();

        return $this->contains(fn (Breakpoint $breakpoint) => in_array($breakpoint->handle, $handles, true));
    }
}
