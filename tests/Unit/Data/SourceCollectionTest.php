<?php

use Pktharindu\FlexiPics\Data\SourceCollection;
use Pktharindu\FlexiPics\ValueObjects\Source;

covers(SourceCollection::class);

test('can create new collection with empty array', function () {
    $sourceCollection = new SourceCollection([]);

    expect($sourceCollection)->toBeEmpty();
});

test('array of source objects should result in collection containing objects', function () {
    $source1 = new Source('type1', 'srcset1', 'media1', 'sizes1');
    $source2 = new Source('type2', 'srcset2', 'media2', 'sizes2');

    $sourceCollection = new SourceCollection([$source1, $source2]);

    expect($sourceCollection)->toHaveCount(2)
        ->toContain($source1)
        ->toContain($source2);
});

test('adding to empty collection should result in collection containing only that object', function () {
    $source = new Source('type', 'srcset', 'media', 'sizes');
    $sourceCollection = new SourceCollection([]);

    $sourceCollection->addSource($source);

    expect($sourceCollection)->toHaveCount(1)
        ->toContain($source);
});

test('array of non source objects should raise invalid argument exception', function () {
    $this->expectException(InvalidArgumentException::class);
    new SourceCollection([new stdClass]);
});

test('adding source with same type and srcset but different media and sizes should result in collection containing both objects', function () {
    $source1 = new Source('type', 'srcset', 'media1', 'sizes1');
    $source2 = new Source('type', 'srcset', 'media2', 'sizes2');
    $sourceCollection = new SourceCollection([$source1]);

    $sourceCollection->addSource($source2);

    expect($sourceCollection)->toHaveCount(2)
        ->toContain($source1)
        ->toContain($source2);
});

test('adding source with same type and srcset but different media and sizes should not modify original collection', function () {
    $source1 = new Source('type', 'srcset', 'media1', 'sizes1');
    $source2 = new Source('type', 'srcset', 'media2', 'sizes2');
    $sourceCollection = new SourceCollection([$source1]);

    $newSourceCollection = $sourceCollection->addSource($source2);

    expect($newSourceCollection)->toHaveCount(2)
        ->toContain($source1)
        ->toContain($source2);
});

test('adding the same source removes the previous source', function () {
    $source1 = new Source('type', 'srcset', 'media1', 'sizes1');
    $source2 = new Source('type', 'srcset', 'media1', 'sizes1');
    $sourceCollection = new SourceCollection([$source1]);

    $newSourceCollection = $sourceCollection->addSource($source2);

    expect($newSourceCollection)->toHaveCount(1)->toContain($source2);
});
