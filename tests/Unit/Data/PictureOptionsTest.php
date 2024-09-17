<?php

use Illuminate\Support\Facades\Config;
use Pktharindu\FlexiPics\Data\PictureOptions;
use Ramsey\Collection\Exception\InvalidPropertyOrMethod;

covers(PictureOptions::class);

it('can be instantiated with all arguments', function () {
    $alt = 'example alt';
    $caption = 'example caption';
    $class = 'example-class';

    $pictureOptions = new PictureOptions($alt, $caption, $class, true);

    expect($pictureOptions)->toBeInstanceOf(PictureOptions::class)
        ->alt->toEqual($alt)
        ->class->toEqual($class)
        ->lazy->toBeTrue();
});

it('can be instantiated with no arguments', function () {
    $pictureOptions = new PictureOptions;

    expect($pictureOptions->alt)->toBeNull()
        ->and($pictureOptions->class)->toBeNull()
        ->and($pictureOptions->lazy)->toBeTrue();
});

it('can be instantiated with only alt argument', function () {
    $alt = 'example alt';

    $pictureOptions = new PictureOptions(alt: $alt);

    expect($pictureOptions->alt)->toEqual($alt)
        ->and($pictureOptions->class)->toBeNull()
        ->and($pictureOptions->lazy)->toBeTrue();
});

it('can be instantiated with only class argument', function () {
    $class = 'example-class';

    $pictureOptions = new PictureOptions(class: $class);

    expect($pictureOptions->alt)->toBeNull()
        ->and($pictureOptions->class)->toEqual($class)
        ->and($pictureOptions->lazy)->toBeTrue();
});

it('can be instantiated with only lazy argument', function () {
    $pictureOptions = new PictureOptions(lazy: false);

    expect($pictureOptions->alt)->toBeNull()
        ->and($pictureOptions->class)->toBeNull()
        ->and($pictureOptions->lazy)->toBeFalse();
});

it('throws InvalidPropertyOrMethod exception if property does not exist', function () {
    $this->expectException(InvalidPropertyOrMethod::class);
    $this->expectExceptionMessage('Property invalidProperty does not exist');

    $pictureOptions = new PictureOptions;
    $pictureOptions->invalidProperty;
});

test('it can set alt property', function () {
    $alt = 'example alt';
    $pictureOptions = new PictureOptions;

    $pictureOptions->setAlt($alt);

    expect($pictureOptions->alt)->toEqual($alt);
});

test('it can set caption property', function () {
    $caption = 'example caption';
    $pictureOptions = new PictureOptions;

    $pictureOptions->setCaption($caption);

    expect($pictureOptions->caption)->toEqual($caption);
});

test('it can set class property', function () {
    $class = 'example-class';
    $pictureOptions = new PictureOptions;

    $pictureOptions->setClass($class);

    expect($pictureOptions->class)->toEqual($class);
});

test('it can set lazy property', function () {
    $pictureOptions = new PictureOptions;

    $pictureOptions->setLazy(false);

    expect($pictureOptions->lazy)->toBeFalse();
});

test('it can set lazy property to config default if null is passed', function () {
    Config::set('statamic.flexipics.lazy_loading', true);
    $pictureOptions = new PictureOptions;

    $pictureOptions->setLazy(null);

    expect($pictureOptions->lazy)->toBeTrue();
});
