<?php

use Pktharindu\FlexiPics\Data\BreakpointCollection;
use Pktharindu\FlexiPics\ValueObjects\Breakpoint;

covers(BreakpointCollection::class);

it('can create new collection with empty array', function () {
    $items = [];

    $collection = new BreakpointCollection($items);

    expect($collection)->toBeInstanceOf(BreakpointCollection::class);
});

it('can create new collection with array of breakpoints', function () {
    $items = [
        new Breakpoint('default', '320|16:9'),
        new Breakpoint('sm', '640|16:9'),
    ];

    $collection = new BreakpointCollection($items);

    expect($collection)->toBeInstanceOf(BreakpointCollection::class);
});

it('adds a breakpoint to the collection', function () {
    $collection = new BreakpointCollection;
    $breakpoint = new Breakpoint('sm', '640|16:9');

    $collection->addBreakpoint($breakpoint);

    expect($collection->getByHandle('sm'))->toBe('640|16:9');
});

it('can return the default value when breakpoint is not found', function () {
    $collection = new BreakpointCollection;
    $breakpoint = new Breakpoint('sm', '640|16:9');

    $collection->addBreakpoint($breakpoint);

    expect($collection->getByHandle('md', 'default'))->toBe('default');
});

it('replaces a breakpoint with the same handle', function () {
    $collection = new BreakpointCollection([
        new Breakpoint('md', '640|16:9'),
    ]);

    $collection->addBreakpoint(new Breakpoint('md', '768|16:9'));

    expect($collection->getByHandle('md'))->toBe('768|16:9')
        ->and($collection->count())->toBe(1);
});

it('keeps other breakpoints when adding a new one', function () {
    $collection = new BreakpointCollection([
        new Breakpoint('sm', '300x200'),
        new Breakpoint('md', '600x400'),
    ]);

    $collection->addBreakpoint(new Breakpoint('lg', '900x600'));

    expect($collection->getByHandle('sm'))->toBe('300x200')
        ->and($collection->getByHandle('md'))->toBe('600x400')
        ->and($collection->getByHandle('lg'))->toBe('900x600')
        ->and($collection->count())->toBe(3);
});

it('throws an exception when adding a non-Breakpoint object', function () {
    $collection = new BreakpointCollection;

    $this->expectException(TypeError::class);

    $collection->addBreakpoint(new stdClass);
});

test('call default with breakpoint instance with handle default', function () {
    $items = [
        new Breakpoint('default', '320|16:9'),
        new Breakpoint('sm', '640|16:9'),
    ];
    $collection = new BreakpointCollection($items);

    $result = $collection->default();

    expect($result)->toBe('320|16:9');
});

it('cannot create new collection with array of non breakpoints', function () {
    $items = [
        new stdClass,
        new stdClass,
    ];

    $this->expectException(InvalidArgumentException::class);

    new BreakpointCollection($items);
});

test('call get without a default value', function () {
    $items = [
        new Breakpoint('sm', '640|16:9|100vw'),
        new Breakpoint('md', '768|16:9|100vw'),
    ];
    $collection = new BreakpointCollection($items);

    $result = $collection->getByHandle('md');

    expect($result)->toBe('768|16:9|100vw');
});

test('default with no default breakpoint', function () {
    $breakpoints = [
        new Breakpoint('sm', '640|16:9|100vw'),
        new Breakpoint('md', '768|16:9|100vw'),
    ];
    $collection = new BreakpointCollection($breakpoints);

    $result = $collection->default();

    expect($result)->toBeNull();
});

test('get with non existing handle and default value', function () {
    $items = [
        new Breakpoint('sm', '640|16:9|100vw'),
        new Breakpoint('md', '768|16:9|100vw'),
    ];
    $collection = new BreakpointCollection($items);

    $result = $collection->getByHandle('lg', '1024|16:9|100vw');

    expect($result)->toBe('1024|16:9|100vw');
});

test('get with non existing handle and without default value', function () {
    $items = [
        new Breakpoint('sm', '640|16:9|100vw'),
        new Breakpoint('md', '768|16:9|100vw'),
    ];
    $collection = new BreakpointCollection($items);

    $result = $collection->getByHandle('lg');

    expect($result)->toBeNull();
});

it('returns false if collection is empty', function () {
    $collection = new BreakpointCollection([]);

    $result = $collection->hasAnyHandle('sm');

    expect($result)->toBeFalse();
});

it('returns true if collection not empty and breakpoint handle matches given key', function () {
    $collection = new BreakpointCollection([
        new Breakpoint('default', '320|16:9'),
        new Breakpoint('sm', '640|16:9'),
        new Breakpoint('md', '768|16:9'),
    ]);

    $result = $collection->hasAnyHandle('sm');

    expect($result)->toBeTrue();
});

it('returns true if given key is array and breakpoint handle matches any of the keys', function () {
    $collection = new BreakpointCollection([
        new Breakpoint('default', '320|16:9'),
        new Breakpoint('sm', '640|16:9'),
        new Breakpoint('md', '768|16:9'),
    ]);

    $result = $collection->hasAnyHandle(['sm', 'xl']);

    expect($result)->toBeTrue();
});

it('returns false if given key is array and no breakpoint handle matches any of the keys', function () {
    $collection = new BreakpointCollection([
        new Breakpoint('default', '320|16:9'),
        new Breakpoint('sm', '640|16:9'),
        new Breakpoint('md', '768|16:9'),
    ]);

    $result = $collection->hasAnyHandle(['lg', 'xl']);

    expect($result)->toBeFalse();
});

it('returns false if given key is string and no breakpoint handle matches it', function () {
    $collection = new BreakpointCollection([
        new Breakpoint('default', '320|16:9'),
        new Breakpoint('sm', '640|16:9'),
        new Breakpoint('md', '768|16:9'),
    ]);

    $result = $collection->hasAnyHandle('2xl');

    expect($result)->toBeFalse();
});

it('throws an exception when hasAnyHandle is passed a non-string or non-array handle', function () {
    $collection = new BreakpointCollection([
        new Breakpoint('default', '320|16:9'),
    ]);

    $this->expectException(TypeError::class);

    $collection->hasAnyHandle(new stdClass);
});
