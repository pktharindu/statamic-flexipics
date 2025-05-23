<?php

use Pktharindu\FlexiPics\ValueObjects\Image;

covers(Image::class);

test('image object with required parameters', function () {
    $src = 'image.jpg';
    $class = 'image-class';

    $image = new Image($src, $class, null, null, null, null, null);

    expect($image->src)->toEqual($src)
        ->and($image->class)->toEqual($class)
        ->and($image->alt)->toBeNull()
        ->and($image->loading)->toBeNull()
        ->and($image->width)->toBeNull()
        ->and($image->height)->toBeNull();
});

test('image object with all parameters', function () {
    $src = 'image.jpg';
    $class = 'image-class';
    $alt = 'Image Alt';
    $caption = 'Caption';
    $loading = 'lazy';
    $width = 100;
    $height = 200;

    $image = new Image($src, $class, $alt, $caption, $loading, $width, $height);

    expect($image->src)->toEqual($src)
        ->and($image->class)->toEqual($class)
        ->and($image->alt)->toEqual($alt)
        ->and($image->caption)->toEqual($caption)
        ->and($image->loading)->toEqual($loading)
        ->and($image->width)->toEqual($width)
        ->and($image->height)->toEqual($height);
});

test('image object with null optional parameters', function () {
    $src = 'image.jpg';
    $class = 'image-class';

    $image = new Image($src, $class, null, null, null, null, null);

    expect($image->src)->toEqual($src)
        ->and($image->class)->toEqual($class)
        ->and($image->alt)->toBeNull()
        ->and($image->loading)->toBeNull()
        ->and($image->width)->toBeNull()
        ->and($image->height)->toBeNull();
});

test('image object with empty string parameters', function () {
    $src = '';
    $class = '';

    $this->expectException(InvalidArgumentException::class);
    new Image($src, $class, null, null, null, null, null);
});

test('image object with negative integer parameters', function () {
    $src = 'image.jpg';
    $class = 'image-class';
    $width = -100;
    $height = -200;

    $image = new Image($src, $class, null, null, null, $width, $height);

    expect($image->src)->toEqual($src)
        ->and($image->class)->toEqual($class)
        ->and($image->alt)->toBeNull()
        ->and($image->loading)->toBeNull()
        ->and($image->width)->toEqual($width)
        ->and($image->height)->toEqual($height);
});

test('image object with zero integer parameters', function () {
    $src = 'image.jpg';
    $class = 'image-class';
    $width = 0;
    $height = 0;

    $image = new Image($src, $class, null, null, null, $width, $height);

    expect($image->src)->toEqual($src)
        ->and($image->class)->toEqual($class)
        ->and($image->alt)->toBeNull()
        ->and($image->loading)->toBeNull()
        ->and($image->width)->toEqual($width)
        ->and($image->height)->toEqual($height);
});
