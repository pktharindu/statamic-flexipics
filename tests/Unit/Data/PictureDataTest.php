<?php

use Pktharindu\FlexiPics\Data\PictureData;
use Pktharindu\FlexiPics\Data\SourceCollection;
use Pktharindu\FlexiPics\ValueObjects\Image;
use Pktharindu\FlexiPics\ValueObjects\Source;

test('instantiation without arguments', function () {
    $pictureData = new PictureData;

    expect($pictureData)->toBeInstanceOf(PictureData::class);
});

test('instantiation with just the image', function () {
    $pictureData = new PictureData;
    $pictureData->setImage(new Image('src', 'class', 'alt', 'lazy', 100, 200));

    expect($pictureData->toArray()['sources'])->toBeInstanceOf(SourceCollection::class);
});

test('set image', function () {
    $image = new Image('src', 'class', 'alt', 'loading', 100, 200);
    $pictureData = new PictureData;

    $pictureData->setImage($image);

    expect($pictureData->toArray()['image'])->toBe($image);
});

test('add sources', function () {
    $source1 = new Source('type1', 'srcset1', 'media1', 'sizes1');
    $source2 = new Source('type2', 'srcset2', 'media2', 'sizes2');
    $pictureData = new PictureData;

    $pictureData->addSources($source1, $source2);
    $pictureData->setImage(new Image('src', 'class', 'alt', 'loading', 100, 200));

    $sources = $pictureData->toArray()['sources'];
    expect($sources)->toHaveCount(2)
        ->and($sources[0])->toBe($source1)
        ->and($sources[1])->toBe($source2);
});

test('add sources with null values', function () {
    $source1 = new Source('type1', 'srcset1', 'media1', 'sizes1');
    $source2 = new Source('type2', 'srcset2', null, null);
    $pictureData = new PictureData;

    $pictureData->addSources($source1, $source2);
    $pictureData->setImage(new Image('src', 'class', 'alt', 'loading', 100, 200));

    $sources = $pictureData->toArray()['sources'];
    expect($sources)->toHaveCount(2)
        ->and($sources[0])->toBe($source1)
        ->and($sources[1])->toBe($source2);
});

test('set image with null values', function () {
    $image = new Image('src', 'class', null, null, null, null);
    $pictureData = new PictureData;

    $pictureData->setImage($image);

    expect($pictureData->toArray()['image'])->toBe($image);
});

test('add sources with duplicate values', function () {
    $source1 = new Source('type1', 'srcset1', 'media1', 'sizes1');
    $source2 = new Source('type2', 'srcset2', 'media2', 'sizes2');
    $pictureData = new PictureData;

    $pictureData->addSources($source1, $source2, $source1);
    $pictureData->setImage(new Image('src', 'class', 'alt', 'loading', 100, 200));

    $sources = $pictureData->toArray()['sources'];
    expect($sources)->toHaveCount(2)
        ->and($sources[0])->toBe($source2)
        ->and($sources[1])->toBe($source1);
});

test('add sources without arguments', function () {
    $pictureData = new PictureData;
    $pictureData->setImage(new Image('src', 'class', 'alt', 'lazy', 100, 200));

    $pictureData->addSources();

    $sources = $pictureData->toArray()['sources'];
    expect($sources)->toBeInstanceOf(SourceCollection::class)
        ->toHaveCount(0);
});

test('to array', function () {
    $pictureData = new PictureData;
    $pictureData->setImage($image = new Image('src', 'class', 'alt', 'loading', 100, 200));
    $expectedArray = [
        'sources' => new SourceCollection,
        'image' => $image,
    ];

    $result = $pictureData->toArray();

    expect($result)->toEqual($expectedArray);
});

test('to json', function () {
    $pictureData = new PictureData;
    $pictureData->setImage(new Image('src', 'class', 'alt', 'lazy', 100, 200));
    $expectedJson = '{"sources":[],"image":{"src":"src","class":"class","alt":"alt","loading":"lazy","width":100,"height":200}}';

    $result = $pictureData->toJson();

    expect($result)->toBe($expectedJson);
});
