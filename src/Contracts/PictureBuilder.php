<?php

declare(strict_types=1);

namespace Pktharindu\FlexiPics\Contracts;

use Pktharindu\FlexiPics\Enums\Mode;
use Pktharindu\FlexiPics\Enums\Orientation;
use Pktharindu\FlexiPics\ValueObjects\Breakpoint;
use Statamic\Assets\Asset;

interface PictureBuilder
{
    public static function make(Asset|string $asset): self;

    public function class(?string $class): self;

    public function alt(?string $text): self;

    public function caption(?string $html): self;

    public function lazy(?bool $lazy): self;

    public function orientation(Orientation $orientation): self;

    public function breakpoints(Breakpoint ...$breakpoints): self;

    public function default(?string $params): self;

    public function mode(Mode $mode): self;

    public function generate(): string;
}
