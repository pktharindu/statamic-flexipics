<picture>
    @foreach ($sources as $source)
        <source type="{{ $source->type }}"
                @isset($source->sizes) sizes="{{ $source->sizes }}" @endisset
                @isset($source->media) media="{{ $source->media }}" @endisset
                srcset="{{ $source->srcset }}"
        >
    @endforeach

    <img src="{{ $image->src }}"
         @isset($image->alt) alt="{{ $image->alt }}" @endisset
         @isset($image->class) class="{{ $image->class }}" @endisset
         @isset($image->loading) loading="{{ $image->loading }}" @endisset
         @isset($image->width) width="{{ $image->width }}" @endisset
         @isset($image->height) height="{{ $image->height }}" @endisset
    >
</picture>
