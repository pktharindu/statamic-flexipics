<?php

declare(strict_types=1);

namespace Pktharindu\FlexiPics;

use Assert\AssertionFailedException;
use Exception;
use Illuminate\Support\Facades\Config;
use InvalidArgumentException;
use Pktharindu\FlexiPics\Contracts\PictureBuilder;
use Pktharindu\FlexiPics\Data\BreakpointCollection;
use Pktharindu\FlexiPics\Data\PictureData;
use Pktharindu\FlexiPics\Data\PictureOptions;
use Pktharindu\FlexiPics\Enums\Mode;
use Pktharindu\FlexiPics\Enums\Orientation;
use Pktharindu\FlexiPics\ValueObjects\Breakpoint;
use Pktharindu\FlexiPics\ValueObjects\Image;
use Pktharindu\FlexiPics\ValueObjects\Size;
use Pktharindu\FlexiPics\ValueObjects\Source;
use Pktharindu\FlexiPics\ValueObjects\SourceData;
use Statamic\Assets\Asset;
use Statamic\Facades\Asset as AssetFacade;
use Statamic\Tags\Context;
use Statamic\Tags\Glide;
use Statamic\Tags\Parameters;
use Stringy\StaticStringy as Stringy;
use Throwable;

class FlexiPics implements PictureBuilder
{
    private Asset $asset;
    private BreakpointCollection $breakpoints;
    private PictureData $pictureData;
    private Glide $glide;
    private PictureOptions $pictureOptions;
    private Orientation $orientation;
    private Mode $mode;

    public function __construct()
    {
        $this->breakpoints = new BreakpointCollection;
        $this->orientation = Orientation::Landscape;
        $this->pictureData = new PictureData;
        $this->pictureOptions = new PictureOptions;
    }

    public static function make(Asset|string $asset): self
    {
        return (new self)->setAsset(self::getAssetInstance($asset))->setupGlide();
    }

    /**
     * @throws InvalidArgumentException
     */
    private static function getAssetInstance(Asset|string $asset): Asset
    {
        /** @var Asset|null $assetInstance */
        $assetInstance = $asset instanceof Asset ? $asset : AssetFacade::find($asset);

        if (! $assetInstance) {
            throw new InvalidArgumentException('Invalid asset provided');
        }

        return $assetInstance;
    }

    public function class(?string $class): self
    {
        $this->pictureOptions->setClass($class);

        return $this;
    }

    public function alt(?string $text): self
    {
        $this->pictureOptions->setAlt($text);

        return $this;
    }

    public function caption(?string $html): self
    {
        $this->pictureOptions->setCaption($html);

        return $this;
    }

    /**
     * if no param is set, config default is used
     */
    public function lazy(?bool $lazy): self
    {
        $this->pictureOptions->setLazy($lazy);

        return $this;
    }

    public function orientation(Orientation $orientation): self
    {
        $this->orientation = $orientation;

        return $this;
    }

    public function breakpoints(Breakpoint ...$breakpoints): self
    {
        collect($breakpoints)->each(fn (Breakpoint $breakpoint) => $this->breakpoint($breakpoint));

        return $this;
    }

    public function breakpoint(Breakpoint $breakpoint): self
    {
        $this->breakpoints->addBreakpoint($breakpoint);

        return $this;
    }

    /**
     * @throws AssertionFailedException
     */
    public function default(?string $params): self
    {
        $this->breakpoints->addBreakpoint(new Breakpoint('default', $params));

        return $this;
    }

    public function mode(Mode $mode): self
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * @throws AssertionFailedException
     * @throws Exception
     */
    public function generate(): string
    {
        $this->generateSourcesForBreakpoints();
        $this->generateDefaultSources($this->breakpoints->default());
        $this->pictureData->setImage($this->makeImage());

        return $this->mode->in([Mode::Json, Mode::Array]) ? $this->json() : $this->html();
    }

    private function setAsset(Asset $asset): self
    {
        $this->asset = $asset;

        return $this;
    }

    /**
     * @throws Throwable
     */
    private function html(): string
    {
        /** @phpstan-ignore-next-line */
        return view('flexipics::flexipics', $this->pictureData->toArray())->render();
    }

    private function json(): string
    {
        return $this->pictureData->toJson();
    }

    /**
     * Breakpoint based sources
     */
    private function generateSourcesForBreakpoints(): void
    {
        if (! $this->breakpoints->hasAnyHandle(array_keys($this->defaultBreakpoints()))) {
            return;
        }

        $this->pictureData->addSources(...$this->makeSourcesForBreakpoints($this->defaultFormats()));

        if ($this->shouldUseOriginalFormatAsFallback()) {
            $this->pictureData->addSources(...$this->makeSourcesForBreakpoints($this->originalFormat()));
        }
    }

    /**
     * Non-breakpoint based image with `size` attribute
     *
     * @throws AssertionFailedException
     */
    private function generateDefaultSources(?string $defaultSize): void
    {
        if (! $defaultSize) {
            return;
        }

        $parsedDefaultSize = $this->parseParam($defaultSize);
        $this->pictureData->addSources($this->makeSource($parsedDefaultSize, $this->defaultFormats()));

        if ($this->shouldUseOriginalFormatAsFallback()) {
            $this->pictureData->addSources($this->makeSource($parsedDefaultSize, $this->originalFormat()));
        }
    }

    /**
     * @return string The original file format of the asset.
     */
    private function originalFormat(): string
    {
        return explode('/', $this->asset->meta()['mime_type'])[1];
    }

    private function defaultFormats(): string
    {
        return Config::string('statamic.flexipics.default_filetype');
    }

    private function shouldUseOriginalFormatAsFallback(): bool
    {
        return Config::boolean('statamic.flexipics.use_original_format_as_fallback') && $this->originalFormat() !== $this->defaultFormats();
    }

    /**
     * Parses the input data string and returns a SourceData object.
     *
     * @param  string  $data  The input data string in the format "{srcset}|{ratio}|{sizes}".
     *
     * @throws AssertionFailedException
     */
    private function parseParam(string $data): SourceData
    {
        $dataParts = explode('|', $data);
        $srcset = $this->parseSizeData(trim($dataParts[0]), $this->calcRatio(trim($dataParts[1] ?? '')));
        $sizes = $dataParts[2] ?? null;

        return new SourceData($srcset, $sizes);
    }

    /**
     * Calculates the ratio based on the given input string.
     *
     * @param  string  $ratio  The input string representing the ratio. If $ratio is set to 'auto', then 'auto' is returned.
     *                         Otherwise, it should be in the format 'width:height' or 'width/height'.
     * @return float|string Returns the calculated ratio as a float if the input string is in the format 'width:height'.
     *                      Returns the input string if it is set to 'auto'.
     *                      Returns the input string as a float if the input string is a single numeric value.
     */
    private function calcRatio(string $ratio): float|string
    {
        if ($ratio === 'auto') {
            return $ratio;
        }

        $parts = explode(':', str_replace('/', ':', $ratio), 2);

        if (count($parts) === 2) {
            [$width, $height] = array_map('floatval', $parts);

            return $height / $width;
        }

        return (float) $ratio;
    }

    /**
     * Generates the alt attribute for the image based on the configured options.
     * If no specific alt attribute is set, it falls back to the 'alt' value from the asset data.
     */
    private function makeAlt(): ?string
    {
        $alt = $this->pictureOptions->alt ?? $this->asset->data()->get('alt');

        if ($alt && Config::boolean('statamic.flexipics.alt_fullstop')) {
            $alt = Stringy::ensureRight(strip_tags($alt), '.');
        }

        return $alt;
    }

    private function makeClass(): string
    {
        return trim((string) $this->pictureOptions->class);
    }

    /**
     * Generate a glide URL with the provided parameters.
     * The source asset is provided through the tag context.
     *
     * @param  array<string, string|float>  $params
     */
    private function makeGlideUrl(array $params): string
    {
        $this->glide->params = Parameters::make(array_merge($params, ['src' => $this->asset]), $this->glide->context);

        return $this->glide->index();
    }

    /**
     * @throws AssertionFailedException
     */
    private function makeImage(): Image
    {
        return new Image(
            src: $this->makeGlideUrl(['width' => $this->smallestSrcWidth(), 'fit' => 'crop_focal']),
            class: $this->makeClass(),
            alt: $this->makeAlt(),
            caption: $this->pictureOptions->caption,
            loading: $this->pictureOptions->lazy ? 'lazy' : null,
            width: $this->asset->width(),
            height: $this->asset->height(),
        );
    }

    /**
     * @throws AssertionFailedException
     */
    private function makeSource(SourceData $sourceData, string $format, ?string $breakpoint = null): Source
    {
        return new Source(
            type: "image/{$format}",
            srcset: $this->makeSrcset($sourceData, $format),
            media: $breakpoint ? "(min-width: {$this->defaultBreakpoints()[$breakpoint]}px)" : null,
            sizes: $sourceData->sizes ?? null,
        );
    }

    /**
     * @return array<string, Source>
     */
    private function makeSourcesForBreakpoints(string $format): array
    {
        return collect($this->defaultBreakpoints())
            ->reject(fn (int $size, string $breakpoint) => $breakpoint === 'default')
            ->sortDesc()
            ->map(function (int $size, string $breakpoint) use ($format) {
                $breakpointData = $this->breakpoints->getByHandle($breakpoint);

                return $breakpointData
                    ? $this->makeSource($this->parseParam($breakpointData), $format, $breakpoint)
                    : null;
            })
            ->filter()
            ->all();
    }

    private function makeSrcset(SourceData $sourceData, string $format): string
    {
        $glideOptions = [
            'format' => $format,
            'fit' => 'crop_focal',
        ];

        return collect($sourceData->srcset)
            ->flatMap(fn (Size $source) => collect($this->multipliers($sourceData))
                ->unique()
                ->map(function (float $multiplier) use ($source, $glideOptions, $sourceData): string {
                    $width = $source->width * $multiplier;
                    $height = $source->height * $multiplier;
                    $options = array_merge($glideOptions, ['width' => $width, 'height' => $height]);
                    $url = $this->makeGlideUrl($options);

                    return $sourceData->sizes ? "{$url} {$width}w" : "{$url} {$multiplier}x";
                })
            )
            ->implode(',');
    }

    /**
     * @return float[]
     */
    private function multipliers(SourceData $sourceData): array
    {
        return $sourceData->sizes ? Config::array('statamic.flexipics.size_multipliers') : Config::array('statamic.flexipics.dpr');
    }

    /**
     * @throws AssertionFailedException
     */
    private function parseSingleSize(string $size, float|string $ratio): Size
    {
        $sizeParts = explode('x', trim($size));
        $width = (float) $sizeParts[0];
        $height = isset($sizeParts[1]) ? (float) $sizeParts[1] : (is_float($ratio) ? $width * $ratio : 0.0);

        return new Size(
            $this->orientation->is(Orientation::Landscape) ? $width : $height,
            $this->orientation->is(Orientation::Portrait) ? $width : $height
        );
    }

    /**
     * Converts a string with srcset information into a structured array.
     * e.g. "300,600x200" -> [ ['width' => 300], ['width' => 600, 'height' => 200] ]
     * Supports a $ratio option to calc height (if no explicit height supplied).
     *
     * @return Size[]
     *
     * @throws AssertionFailedException
     */
    private function parseSizeData(string $sizeData, float|string $ratio = 'auto'): array
    {
        $sizes = explode(',', $sizeData);

        return array_map(fn (string $size) => $this->parseSingleSize($size, $ratio), $sizes);
    }

    /**
     * Setup everything we need for Glide image generation.
     * This method simply utilises Statamics own `{{ glide }}` tag to generate image urls.
     */
    private function setupGlide(): self
    {
        $this->glide = new Glide;
        $this->glide->method = 'index';
        $this->glide->tag = 'glide:index';
        $this->glide->isPair = false;
        $this->glide->context = new Context;
        $this->glide->params = Parameters::make(['src' => $this->asset], $this->glide->context);

        return $this;
    }

    private function smallestSrcWidth(): int
    {
        return Config::integer('statamic.flexipics.min_width');
    }

    /**
     * @return array<string, int>
     */
    private function defaultBreakpoints(): array
    {
        return Config::array('statamic.flexipics.breakpoints');
    }
}
