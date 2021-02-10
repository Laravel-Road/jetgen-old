<?php

namespace LaravelRoad\JetGen\Tests;

use Illuminate\Foundation\Application;
use LaravelRoad\JetGen\Providers\JetGenServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    /**
     * @param $app
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [
            JetGenServiceProvider::class,
        ];
    }

    /**
     * @param Application $app
     */
    protected function getEnvironmentSetUp($app): void
    {
        $configs = $this->configs();

        $app['config']->set('jetgen.blueprint_types', $configs['blueprint_types']);
    }

    /**
     * @return array
     */
    protected function configs(): array
    {
        return require __DIR__ . '/../config/jetgen.php';
    }
}
