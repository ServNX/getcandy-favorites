<?php

namespace Servnx\GetCandyFavorite\Tests;

use Livewire\LivewireServiceProvider;
use Servnx\GetCandyFavorite\GetCandyFavoritesServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            GetCandyFavoritesServiceProvider::class,
            LivewireServiceProvider::class,
        ];
    }

    /**
     * Define database migrations.
     *
     * @return void
     */
    protected function defineDatabaseMigrations()
    {
        $this->loadLaravelMigrations();
    }

    protected function getEnvironmentSetUp($app)
    {
        // perform environment setup
    }
}
