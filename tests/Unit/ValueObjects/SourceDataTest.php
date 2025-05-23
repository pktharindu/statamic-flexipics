<?php

use Assert\AssertionFailedException;
use Pktharindu\FlexiPics\ValueObjects\Size;
use Pktharindu\FlexiPics\ValueObjects\SourceData;

covers(SourceData::class);

it('can instantiate with non empty array and null sizes', function () {
    $srcset = [new Size(10, 20), new Size(30, 40)];
    $sizes = null;

    $sourceData = new SourceData($srcset, $sizes);

    expect($sourceData)->toBeInstanceOf(SourceData::class);
});

it('can instantiate with non empty array and non null sizes', function () {
    $srcset = [new Size(10, 20), new Size(30, 40)];
    $sizes = '100vw';

    $sourceData = new SourceData($srcset, $sizes);

    expect($sourceData)->toBeInstanceOf(SourceData::class);
});

it('cannot instantiate with empty array and null sizes', function () {
    $srcset = [];
    $sizes = null;

    $this->expectException(AssertionFailedException::class);
    new SourceData($srcset, $sizes);
});

it('cannot instantiate with null srcset', function () {
    $srcset = null;
    $sizes = '100vw';

    $this->expectException(TypeError::class);

    new SourceData($srcset, $sizes);
});

it('cannot instantiate with non array srcset', function () {
    $srcset = 'invalid';
    $sizes = '100vw';

    $this->expectException(TypeError::class);

    new SourceData($srcset, $sizes);
});

it('cannot instantiate with array of non size objects', function () {
    $srcset = [new stdClass, new stdClass];
    $sizes = '100vw';

    $this->expectException(AssertionFailedException::class);

    new SourceData($srcset, $sizes);
});
