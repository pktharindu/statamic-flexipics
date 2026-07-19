<?php

namespace Pktharindu\FlexiPics\Tests;

use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Pktharindu\FlexiPics\ServiceProvider;
use Statamic\Addons\Manifest;
use Statamic\Facades\Stache;
use Statamic\Providers\StatamicServiceProvider;
use Statamic\Statamic;

abstract class TestCase extends OrchestraTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('statamic.assets.image_manipulation.secure', false);
    }

    protected function tearDown(): void
    {
        Stache::clear();

        parent::tearDown();
    }

    /**
     * Load package service provider
     *
     * @param  Application  $app
     */
    protected function getPackageProviders($app): array
    {
        return [
            StatamicServiceProvider::class,
            ServiceProvider::class,
        ];
    }

    /**
     * Load package alias
     *
     * @param  Application  $app
     */
    protected function getPackageAliases($app): array
    {
        return [
            'Statamic' => Statamic::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app->make(Manifest::class)->manifest = [
            'pktharindu/statamic-flexipics' => [
                'id' => 'pktharindu/statamic-flexipics',
                'namespace' => 'Pktharindu\\FlexiPics',
            ],
        ];
    }

    /**
     * Resolve the Application Configuration and set the Statamic configuration
     *
     * @param  Application  $app
     */
    protected function resolveApplicationConfiguration($app): void
    {
        parent::resolveApplicationConfiguration($app);

        // Define config settings for all of our tests
        $app['config']->set('statamic.flexipics', require (__DIR__ . '/../config/flexipics.php'));
    }
}
