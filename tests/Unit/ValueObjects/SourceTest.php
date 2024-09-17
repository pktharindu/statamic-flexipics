<?php

use Assert\AssertionFailedException;
use Pktharindu\FlexiPics\ValueObjects\Source;

covers(Source::class);

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

test('equals method with different types', function () {
    $source1 = new Source('image', 'image.jpg', 'screen', '100vw');
    $source2 = new Source('video', 'image.jpg', 'screen', '100vw');

    $result = $source1->equals($source2);

    expect($result)->toBeFalse();
});

test('equals method with different srcset', function () {
    $source1 = new Source('image', 'image1.jpg', 'screen', '100vw');
    $source2 = new Source('image', 'image2.jpg', 'screen', '100vw');

    $result = $source1->equals($source2);

    expect($result)->toBeFalse();
});

test('equals method with different media', function () {
    $source1 = new Source('image', 'image.jpg', 'screen', '100vw');
    $source2 = new Source('image', 'image.jpg', 'screen2', '100vw');

    $result = $source1->equals($source2);

    expect($result)->toBeFalse();
});

test('equals method with different sizes', function () {
    $source1 = new Source('image', 'image.jpg', 'screen', '100vw');
    $source2 = new Source('image', 'image.jpg', 'screen', '50vw');

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
