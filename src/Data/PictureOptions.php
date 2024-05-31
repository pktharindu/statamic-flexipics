<?php

declare(strict_types=1);

namespace Pktharindu\FlexiPics\Data;

use Ramsey\Collection\Exception\InvalidPropertyOrMethod;

/**
 * Class PictureOptions.
 *
 * @property-read string|null $alt
 * @property-read string|null $class
 * @property-read bool $lazy
 */
class PictureOptions
{
    private ?bool $lazy;
    private bool $defaultLoading;

    public function __construct(private ?string $alt = null, private ?string $class = null, ?bool $lazy = null)
    {
        $this->defaultLoading = (bool) config('statamic.flexipics.lazy_loading', true);
        $this->setLazy($lazy);
    }

    public function setAlt(?string $alt): PictureOptions
    {
        $this->alt = $alt;

        return $this;
    }

    public function setClass(?string $class): PictureOptions
    {
        $this->class = $class;

        return $this;
    }

    public function setLazy(?bool $lazy): PictureOptions
    {
        $this->lazy = $lazy ?? $this->defaultLoading;

        return $this;
    }

    /**
     * @throws InvalidPropertyOrMethod
     */
    public function __get(string $property): string|bool|null
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }

        throw new InvalidPropertyOrMethod("Property {$property} does not exist");
    }
}
