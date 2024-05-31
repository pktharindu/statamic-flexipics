<?php

use Illuminate\Support\Facades\Config;
use Pktharindu\FlexiPics\Enums\Mode;
use Pktharindu\FlexiPics\Enums\Orientation;
use Pktharindu\FlexiPics\FlexiPics;
use Pktharindu\FlexiPics\ValueObjects\Breakpoint;
use Statamic\Assets\Asset;

it('can create flexipics instance with asset', function () {
    $asset = $this->mock(Asset::class);

    $flexiPics = FlexiPics::make($asset);

    expect($flexiPics)->toBeInstanceOf(FlexiPics::class);
});

it('throws exception when invalid asset provided', function () {
    $this->expectException(InvalidArgumentException::class);

    FlexiPics::make('invalid_asset');
});

it('can generate html output', function () {
    $asset = $this->mock(Asset::class);
    $asset->shouldReceive('data->get')
        ->once()
        ->with('alt')
        ->andReturn('image alt');
    $asset->shouldReceive('width')
        ->once()
        ->andReturn(250);
    $asset->shouldReceive('height')
        ->once()
        ->andReturn(100);

    $output = FlexiPics::make($asset)
        ->class('image-class')
        ->lazy(true)
        ->orientation(Orientation::LANDSCAPE)
        ->mode(Mode::HTML)
        ->generate();

    expect($output)->toContain(
        '<picture>',
        '<img',
        'alt="image alt."',
        'src="',
        'class="image-class"',
        'loading="lazy"',
        'width="250"',
        'height="100"',
        '</picture>'
    );
});

it('can generate json output', function () {
    $asset = $this->mock(Asset::class);
    $asset->shouldReceive('data->get')
        ->once()
        ->with('alt')
        ->andReturn('image alt');
    $asset->shouldReceive('width')
        ->once()
        ->andReturn(250);
    $asset->shouldReceive('height')
        ->once()
        ->andReturn(100);

    $output = FlexiPics::make($asset)
        ->class('image-class')
        ->lazy(true)
        ->orientation(Orientation::LANDSCAPE)
        ->mode(Mode::JSON)
        ->generate();

    expect($output)->toBeJson()
        ->json()
        ->toHaveCount(2)
        ->sources->toBeEmpty()
        ->image->toBeArray()
        ->toHaveCount(6)
        ->toHaveKey('alt')
        ->toHaveKey('src')
        ->toHaveKey('class')
        ->toHaveKey('loading')
        ->toHaveKey('width')
        ->toHaveKey('height')
        ->image->alt->toBe('image alt.')
        ->image->src->not->toBeEmpty()
        ->image->class->toBe('image-class')
        ->image->loading->toBe('lazy')
        ->image->width->toBe(250)
        ->image->height->toBe(100);
});

it('can generate sources for breakpoints', function () {
    $asset = $this->mock(Asset::class);
    $asset->shouldReceive('data->get')
        ->once()
        ->with('alt')
        ->andReturn('image alt');
    $asset->shouldReceive('meta')
        ->times(4)
        ->andReturn([
            'mime_type' => 'image/jpeg',
        ]);
    $asset->shouldReceive('width')
        ->once()
        ->andReturn(250);
    $asset->shouldReceive('height')
        ->once()
        ->andReturn(100);

    Config::set('statamic.flexipics', [
        'dpr' => [1, 2],
        'size_multipliers' => [1, 1.5, 2],
        'default_filetype' => 'webp',
        'min_width' => 300,
        'lazy_loading' => true,
        'use_original_format_as_fallback' => true,
        'breakpoints' => [
            'default' => 0,
            'sm' => 640,
            'md' => 768,
        ],
        'alt_fullstop' => true,
    ]);

    $output = FlexiPics::make($asset)
        ->breakpoints(new Breakpoint('default', '320'), new Breakpoint('sm', '640'), new Breakpoint('md', '768|auto'))
        ->default('320|16:9|100vw')
        ->mode(Mode::JSON)
        ->generate();

    expect($output)->toBeJson()
        ->json()
        ->toHaveCount(2)
        ->toHaveKey('sources')->not->toBeEmpty()
        ->toHaveKey('image')->not->toBeEmpty()
        ->sources->toHaveCount(6)->each->toHaveKeys(['type', 'media', 'srcset', 'sizes'])
        ->image->alt->toBe('image alt.')
        ->image->src->not->toBeEmpty()
        ->image->loading->toBe('lazy')
        ->image->width->toBe(250)
        ->image->height->toBe(100);
});

it('can handle orientation', function () {
    $asset = $this->mock(Asset::class);
    $asset->shouldReceive('width')
        ->once()
        ->andReturn(250);
    $asset->shouldReceive('height')
        ->once()
        ->andReturn(100);

    $output = FlexiPics::make($asset)
        ->alt('image alt')
        ->orientation(Orientation::PORTRAIT)
        ->mode(Mode::JSON)
        ->generate();

    expect($output)->toBeJson()
        ->json()
        ->toHaveKey('image')
        ->image->toHaveKey('width')
        ->image->toHaveKey('height');
});
