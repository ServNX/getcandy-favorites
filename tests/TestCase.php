<?php

namespace Servnx\GetCandyFavorite\Tests;

use Cartalyst\Converter\Laravel\ConverterServiceProvider;
use GetCandy\FieldTypes\TranslatedText;
use GetCandy\GetCandyServiceProvider;
use GetCandy\Models\Product;
use GetCandy\Models\ProductType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Kalnoy\Nestedset\NestedSetServiceProvider;
use Livewire\LivewireServiceProvider;
use Servnx\GetCandyFavorite\GetCandyFavoritesServiceProvider;
use Servnx\GetCandyFavorite\Tests\Stubs\Post;
use Servnx\GetCandyFavorite\Tests\Stubs\User;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected $table_prefix;
    private $productType;

    public function setUp(): void
    {
        parent::setUp();

        Event::fake();

        config(['auth.providers.users.model' => User::class]);

        $this->productType = ProductType::create([
            'name' => 'Test Product Type',
        ]);

        $this->table_prefix = config('getcandy.database.table_prefix');
    }

    protected function getPackageProviders($app)
    {
        return [
            GetCandyServiceProvider::class,
            GetCandyFavoritesServiceProvider::class,
            LivewireServiceProvider::class,
            ConverterServiceProvider::class,
            NestedSetServiceProvider::class,
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
        $this->loadMigrationsFrom(__DIR__ . '/Stubs/migrations');
        $this->loadMigrationsFrom(dirname(__DIR__).'/migrations');
    }

    protected function getEnvironmentSetUp($app)
    {
        //
    }

    protected function CreateUser($name = 'test', $email = null)
    {
        if ($email === null) {
            $email = $name . '@email.com';
        }

        return User::create([
            'name'     => $name,
            'email'    => $email,
            'password' => 'test123'
        ]);
    }

    protected function CreatePost($title = 'test')
    {
        return Post::create([
            'title' => $title,
        ]);
    }

    protected function CreateProduct($name = 'Test Product')
    {
        return Product::create([
            'product_type_id' => $this->productType->id,
            'status'          => 'published',
            'brand'           => 'KARVEC',
            'attribute_data'  => [
                'name'        => new TranslatedText([
                    'en' => $name
                ]),
                'description' => new TranslatedText([
                    'en' => 'Description'
                ]),
            ]
        ]);
    }

    protected function getQueryLog(\Closure $callback): \Illuminate\Support\Collection
    {
        $sqls = collect([]);
        DB::listen(function ($query) use ($sqls) {
            $sqls->push(['sql' => $query->sql, 'bindings' => $query->bindings]);
        });

        $callback();

        return $sqls;
    }
}
