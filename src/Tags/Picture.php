<?php

declare(strict_types=1);

namespace Pktharindu\FlexiPics\Tags;

use Assert\AssertionFailedException;
use Illuminate\Support\Facades\Config;
use Pktharindu\FlexiPics\Contracts\PictureBuilder;
use Pktharindu\FlexiPics\Enums\Mode;
use Pktharindu\FlexiPics\Enums\Orientation;
use Pktharindu\FlexiPics\ValueObjects\Breakpoint;
use Statamic\Assets\Asset;
use Statamic\Tags\Tags;

class Picture extends Tags
{
    /**
     * @var string[]
     */
    protected static $aliases = ['flexipics'];

    public function __construct(protected PictureBuilder $pictureBuilder) {}

    /**
     * {{ picture src="[src]" }}.
     *
     * Where `src` is an asset, a path or url.
     *
     * @throws AssertionFailedException
     */
    public function index(): string
    {
        $asset = $this->params->get(['src', 'id', 'path']) ?? '';

        return $this->output($asset, $this->mode());
    }

    /**
     * {{ picture:json src="[src]" }}.
     *
     * Where `src` is an asset, a path or url.
     *
     * @throws AssertionFailedException
     */
    public function json(): string
    {
        $asset = $this->params->get(['src', 'id', 'path']) ?? '';

        return $this->output($asset, Mode::Json);
    }

    /**
     * The mode method retrieves the output mode from the parameters.
     * If no mode is specified in the parameters, it defaults to HTML.
     */
    public function mode(): Mode
    {
        return Mode::tryFrom($this->params->get('output', '')) ?? Mode::Html;
    }

    /**
     * The orientation method retrieves the orientation from the parameters.
     * If no orientation is specified in the parameters, it defaults to landscape.
     */
    public function orientation(): Orientation
    {
        return Orientation::tryFrom($this->params->get(['orientation', 'ori'], '')) ?? Orientation::Landscape;
    }

    /**
     * @return Breakpoint[]
     *
     * @throws AssertionFailedException
     */
    public function breakpoints(): array
    {
        $breakpoints = $this->params->only(array_keys(Config::array('statamic.flexipics.breakpoints')))->all();

        return array_map(static fn (?string $params, int|string $handle) => new Breakpoint((string) $handle, $params), $breakpoints, array_keys($breakpoints));
    }

    /**
     * @throws AssertionFailedException
     */
    protected function output(Asset|string $asset, Mode $mode): string
    {
        return $this->pictureBuilder::make($asset)
            ->class($this->params->get('class'))
            ->alt($this->params->get('alt'))
            ->caption($this->params->get('caption'))
            ->lazy($this->params->get('lazy'))
            ->orientation($this->orientation())
            ->breakpoints(...$this->breakpoints())
            ->default($this->params->get(['size', 'default']))
            ->mode($mode)
            ->generate();
    }

    /**
     * {{ picture:[field] }}.
     *
     * Where `field` is the variable containing the image ID.
     * Notice that this won't work if the field handle is `json`.
     *
     * @param  string  $method
     * @param  mixed[]  $args
     *
     * @throws AssertionFailedException
     */
    public function __call($method, $args): string // @pest-ignore-type
    {
        $tag = explode(':', $this->tag, 2)[1];

        if (! $asset = $this->context->value($tag)) {
            return '';
        }

        return $this->output($asset, $this->mode());
    }
}
