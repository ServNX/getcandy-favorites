<?php

namespace Servnx\GetCandyFavorite;

use GetCandy\Hub\Facades\Menu;
use GetCandy\Models\Product;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Servnx\GetCandyFavorite\Http\Livewire\Components\Dashboard;
use Servnx\GetCandyFavorite\Mixins\GetCandyFavoriteableMixins;

class GetCandyFavoritesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerMixins();

        if (config('favorite.hub')) {
            $this->bootHub();
        }

        $this->publishes([
            \dirname(__DIR__) . '/config/favorite.php' => config_path('favorite.php'),
        ], 'getcandy-favorites');

        $this->publishes([
            \dirname(__DIR__) . '/migrations/' => database_path('migrations'),
        ], 'getcandy-favorites');

        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(\dirname(__DIR__) . '/migrations/');
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(
            \dirname(__DIR__) . '/config/favorite.php',
            'favorite'
        );
    }

    /**
     * Using macro mixins, we extend on the GetCandy models here.
     *
     * @return void
     * @throws \ReflectionException
     */
    private function registerMixins()
    {
        Product::mixin(new GetCandyFavoriteableMixins());
    }

    /**
     * If using GetCandy Admin Hub, extend the hub here.
     *
     * @return void
     */
    private function bootHub()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'favorites');

        $this->registerLivewireComponents();

        $slot = Menu::slot('sidebar');

        $slot->addItem(function ($item) {
            $item->name(
            // __('menu.sidebar.tickets')
                'Favorites'
            )->route('hub.favorites.index')
                ->icon('heart');
        });
    }

    /**
     * Register all Livewire Components here (not pages)
     *
     * @return void
     */
    private function registerLivewireComponents()
    {
        Livewire::component('favorites.components.dashboard', Dashboard::class);
    }
}
