<?php

namespace Servnx\GetCandyFavorite\Tests\Feature;

use GetCandy\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Servnx\GetCandyFavorite\Events\Favorited;
use Servnx\GetCandyFavorite\Events\Unfavorited;
use Servnx\GetCandyFavorite\Tests\Stubs\User;
use Servnx\GetCandyFavorite\Tests\TestCase;

class CoreFeaturesTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_can_favorite_product_and_dispatches_events()
    {
        $user = $this->CreateUser();
        $product = $this->CreateProduct();

        $user->favorite($product);

        Event::assertDispatched(Favorited::class, function ($event) use ($user, $product) {
            return $event->favorite->favoriteable instanceof Product
                && $event->favorite->user instanceof User
                && $event->favorite->user->id === $user->id
                && $event->favorite->favoriteable->id === $product->id;
        });

        $this->assertTrue($user->hasFavorited($product));
        $this->assertTrue($product->hasBeenFavoritedBy($user));
    }

    public function test_can_unfavorite_product_and_dispatches_events()
    {
        $user = $this->CreateUser();
        $product = $this->CreateProduct();

        $user->favorite($product);
        $this->assertTrue($user->hasFavorited($product));

        $user->unfavorite($product);

        Event::assertDispatched(Unfavorited::class, function ($event) use ($user, $product) {
            return $event->favorite->favoriteable instanceof Product
                && $event->favorite->user instanceof User
                && $event->favorite->user->id === $user->id
                && $event->favorite->favoriteable->id === $product->id;
        });

        $this->assertFalse($user->hasFavorited($product));
    }
}
