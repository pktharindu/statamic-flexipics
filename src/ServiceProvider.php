<?php

declare(strict_types=1);

namespace Pktharindu\FlexiPics;

use Illuminate\Support\Facades\Config;
use Pktharindu\FlexiPics\Contracts\PictureBuilder;
use Pktharindu\FlexiPics\Tags\Picture;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    public const string NAMESPACE = 'flexipics';

    public const string VENDOR_VIEWS_KEY = self::NAMESPACE . '-views';

    public const string VENDOR_CONFIG_KEY = self::NAMESPACE . '-config';

    protected $tags = [
        Picture::class,
    ];

    public function bootAddon(): void
    {
        $this->bootAddonViews()
            ->bootAddonConfig()
            ->bindPictureBuilder();
    }

    protected function bootAddonViews(): self
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', self::NAMESPACE);

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/' . self::NAMESPACE),
        ], self::VENDOR_VIEWS_KEY);

        return $this;
    }

    protected function bootAddonConfig(): self
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/flexipics.php', 'statamic.flexipics');

        $this->publishes([
            __DIR__ . '/../config/flexipics.php' => config_path('statamic/flexipics.php'),
        ], self::VENDOR_CONFIG_KEY);

        return $this;
    }

    private function bindPictureBuilder(): void
    {
        $this->app->bind(PictureBuilder::class, Config::string('statamic.flexipics.picture_builder'));
    }
}
