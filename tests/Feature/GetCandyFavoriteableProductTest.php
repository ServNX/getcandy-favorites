<?php

namespace Servnx\GetCandyFavorite\Tests\Feature;

use GetCandy\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Servnx\GetCandyFavorite\Events\Favorited;
use Servnx\GetCandyFavorite\Events\Unfavorited;
use Servnx\GetCandyFavorite\Tests\Stubs\Post;
use Servnx\GetCandyFavorite\Tests\Stubs\User;
use Servnx\GetCandyFavorite\Tests\TestCase;

class GetCandyFavoriteableProductTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_can_favorite_a_getcandy_product()
    {
        $user = $this->CreateUser();
        $product = $this->CreateProduct();

        $user->favorite($product);

        $this->assertTrue($user->hasFavorited($product));
        $this->assertTrue($product->hasBeenFavoritedBy($user));
    }

    public function test_can_unfavorite_a_getcandy_product()
    {
        $user = $this->CreateUser();
        $product = $this->CreateProduct();

        $user->favorite($product);
        $this->assertTrue($user->hasFavorited($product));

        $user->unfavorite($product);

        $this->assertFalse($user->hasFavorited($product));
    }

    public function test_can_retrieve_favorites_with_product_type()
    {
        $user = $this->CreateUser();

        $product1 = $this->CreateProduct();
        $product2 = $this->CreateProduct();
        $post1 = $this->CreatePost();
        $post2 = $this->CreatePost();

        $user->favorite($product1);
        $user->favorite($product2);
        $user->favorite($post1);
        $user->favorite($post2);

        $this->assertSame(4, $user->favorites()->count());
        $this->assertSame(2, $user->favorites()->withType(Product::class)->count());
    }
}
