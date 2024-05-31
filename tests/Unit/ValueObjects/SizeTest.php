<?php

use Assert\AssertionFailedException;
use Pktharindu\FlexiPics\ValueObjects\Size;

it('can instantiate with valid width and height values', function () {
    $width = 10.5;
    $height = 20.3;

    $size = new Size($width, $height);

    expect($size->width)->toEqual($width)
        ->and($size->height)->toEqual($height);
});

it('can instantiate with zero width and height values', function () {
    $width = 0;
    $height = 0;

    $size = new Size($width, $height);

    expect($size->width)->toEqual($width)
        ->and($size->height)->toEqual($height);
});

it('can access width and height values', function () {
    $width = 10.5;
    $height = 20.3;
    $size = new Size($width, $height);

    $actualWidth = $size->width;
    $actualHeight = $size->height;

    expect($actualWidth)->toEqual($width)
        ->and($actualHeight)->toEqual($height);
});

it('cannot instantiate with non numeric values', function () {
    $width = 'invalid';
    $height = 'invalid';

    $this->expectException(TypeError::class);
    new Size($width, $height);
});

it('cannot instantiate with negative values', function () {
    $width = -10.5;
    $height = -20.3;

    $this->expectException(AssertionFailedException::class);
    new Size($width, $height);
});
