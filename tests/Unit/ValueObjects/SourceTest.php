<?php

use Assert\AssertionFailedException;
use Pktharindu\FlexiPics\ValueObjects\Source;

test('create new instance with valid arguments', function () {
    $type = 'image';
    $srcset = 'image.jpg';
    $media = 'screen';
    $sizes = '100vw';

    $source = new Source($type, $srcset, $media, $sizes);

    expect($source)->toBeInstanceOf(Source::class);
});

test('equals method with identical objects', function () {
    $type = 'image';
    $srcset = 'image.jpg';
    $media = 'screen';
    $sizes = '100vw';

    $source1 = new Source($type, $srcset, $media, $sizes);
    $source2 = new Source($type, $srcset, $media, $sizes);

    $result = $source1->equals($source2);

    expect($result)->toBeTrue();
});

test('equals method with different objects', function () {
    $source1 = new Source('image', 'image.jpg', 'screen', '100vw');
    $source2 = new Source('video', 'video.mp4', 'screen', '50vw');

    $result = $source1->equals($source2);

    expect($result)->toBeFalse();
});

test('create new instance with empty type argument', function () {
    $this->expectException(AssertionFailedException::class);

    new Source('', 'image.jpg', 'screen', '100vw');
});

test('create new instance with empty srcset argument', function () {
    $this->expectException(AssertionFailedException::class);

    new Source('image', '', 'screen', '100vw');
});
