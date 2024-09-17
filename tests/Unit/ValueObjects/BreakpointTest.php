<?php

use Pktharindu\FlexiPics\ValueObjects\Breakpoint;

covers(Breakpoint::class);

it('can create breakpoint with handle and size', function () {
    $handle = 'default';
    $size = '320|16:9|100vw';
    $breakpoint = new Breakpoint($handle, $size);

    expect($breakpoint->handle)->toEqual($handle)
        ->and($breakpoint->size)->toEqual($size);
});

it('can create breakpoint with null size', function () {
    $handle = 'default';
    $size = null;
    $breakpoint = new Breakpoint($handle, $size);

    expect($breakpoint->handle)->toEqual($handle)
        ->and($breakpoint->size)->toBeNull();
});

it('cannot create breakpoint with empty handle', function () {
    $handle = '';
    $size = '320|16:9|100vw';

    $this->expectException(InvalidArgumentException::class);
    new Breakpoint($handle, $size);
});

it('can compare two breakpoints', function () {
    $breakpoint1 = new Breakpoint('default', '320|16:9|100vw');

    $breakpoint2 = new Breakpoint('default', '640|16:9|50vw');

    expect($breakpoint1->equals($breakpoint2))->toBeTrue();
});

it('can compare two different breakpoints', function () {
    $breakpoint1 = new Breakpoint('sm', '320|16:9|100vw');

    $breakpoint2 = new Breakpoint('default', '320|16:9|100vw');

    expect($breakpoint1->equals($breakpoint2))->toBeFalse();
});
