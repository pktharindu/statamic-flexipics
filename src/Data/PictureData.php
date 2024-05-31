<?php

namespace Pktharindu\FlexiPics\Data;

use Pktharindu\FlexiPics\ValueObjects\Image;
use Pktharindu\FlexiPics\ValueObjects\Source;

class PictureData
{
    private SourceCollection $sources;

    private Image $image;

    public function __construct()
    {
        $this->sources = new SourceCollection;
    }

    public function addSources(Source ...$sources): void
    {
        foreach ($sources as $source) {
            $this->sources->addSource($source);
        }
    }

    public function setImage(Image $image): void
    {
        $this->image = $image;
    }

    /**
     * @return array<string, SourceCollection|Image>
     */
    public function toArray(): array
    {
        return [
            'sources' => $this->sources,
            'image' => $this->image,
        ];
    }

    public function toJson(): string
    {
        return collect($this->toArray())->toJson();
    }
}
