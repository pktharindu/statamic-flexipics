<?php

describe('Architecture', function () {
    arch()->preset()->php();
    arch()->preset()->security();

    $namespace = 'Pktharindu\FlexiPics';

    test('classes')
        ->expect($namespace)
        ->toBeClasses()
        ->ignoring("{$namespace}\Enums")
        ->ignoring("{$namespace}\Contracts");

    test('contracts')
        ->expect("{$namespace}\Contracts")
        ->toBeInterfaces();

    test('data')
        ->expect("{$namespace}\Data")
        ->toBeClasses()
        ->not->toHaveProtectedMethods()
        ->not->toBeAbstract();

    test('enums')
        ->expect($namespace)
        ->not->toBeEnums()
        ->ignoring("{$namespace}\Enums")
        ->and("{$namespace}\Enums")
        ->toBeEnums();

    test('value objects')
        ->expect("{$namespace}\ValueObjects")
        ->toBeReadonly()
        ->toBeFinal();

    test('debug helpers')
        ->expect(['dd', 'ddd', 'dump', 'env', 'exit', 'ray'])
        ->not->toBeUsed();

    test('strict types')
        ->expect($namespace)
        ->toUseStrictTypes()
        ->toUseStrictEquality();
});
