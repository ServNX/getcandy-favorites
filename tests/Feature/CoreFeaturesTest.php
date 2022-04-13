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

class CoreFeaturesTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_can_favorite_own_model_using_favoriteable_trait()
    {
        $user = $this->CreateUser();
        $post = $this->CreatePost();

        $user->favorite($post);

        $this->assertTrue($user->hasFavorited($post));
        $this->assertTrue($post->hasBeenFavoritedBy($user));
    }

    public function test_can_unfavorite_own_model_using_favoriteable_trait()
    {
        $user = $this->CreateUser();
        $post = $this->CreatePost();

        $user->favorite($post);
        $this->assertTrue($user->hasFavorited($post));

        $user->unfavorite($post);
        $this->assertFalse($user->hasFavorited($post));
    }

    public function test_dispatches_favorited_event()
    {
        $user = $this->CreateUser();
        $post = $this->CreatePost();

        $user->favorite($post);

        Event::assertDispatched(Favorited::class, function ($event) use ($user, $post) {
            return $event->favorite->favoriteable instanceof Post
                && $event->favorite->user instanceof User
                && $event->favorite->user->id === $user->id
                && $event->favorite->favoriteable->id === $post->id;
        });
    }

    public function test_dispatches_unfravorited_event()
    {
        $user = $this->CreateUser();
        $post = $this->CreatePost();

        $user->favorite($post);
        $user->unfavorite($post);

        Event::assertDispatched(Unfavorited::class, function ($event) use ($user, $post) {
            return $event->favorite->favoriteable instanceof Post
                && $event->favorite->user instanceof User
                && $event->favorite->user->id === $user->id
                && $event->favorite->favoriteable->id === $post->id;
        });
    }

    public function test_has_favorited()
    {
        $user = $this->CreateUser();
        $post = $this->CreatePost();

        $user->favorite($post);
        $user->favorite($post);
        $user->favorite($post);
        $user->favorite($post);

        $this->assertTrue($user->hasFavorited($post));
        $this->assertTrue($post->hasBeenFavoritedBy($user));
        $this->assertDatabaseCount(
            $this->table_prefix . 'favorites',
            1
        );

        $user->unfavorite($post);
        $this->assertFalse($user->hasFavorited($post));
        $this->assertFalse($post->hasBeenFavoritedBy($user));
        $this->assertDatabaseCount(
            $this->table_prefix . 'favorites',
            0
        );
    }

    public function test_model_favoriters()
    {
        $user1 = $this->CreateUser('mike');
        $user2 = $this->CreateUser('allen');
        $user3 = $this->CreateUser('taylor');

        $post = $this->CreatePost();

        $user1->favorite($post);
        $user2->favorite($post);

        $this->assertFalse($post->hasBeenFavoritedBy($user3));
        $this->assertInstanceOf(User::class, $post->favoriters->first());
        $this->assertCount(2, $post->favoriters);
        $this->assertSame('mike', $post->favoriters->first()->name);
        $this->assertSame('allen', $post->favoriters[1]['name']);
    }

    public function test_eager_loading()
    {
        $user = $this->CreateUser();

        $post1 = $this->CreatePost();
        $post2 = $this->CreatePost();
        $post3 = $this->CreatePost();
        $post4 = $this->CreatePost();

        $user->favorite($post1);
        $user->favorite($post2);
        $user->favorite($post3);
        $user->favorite($post4);

        $sqls = $this->getQueryLog(function () use ($user) {
            $user->load('favorites.favoriteable');
        });
        $this->assertSame(2, $sqls->count());

        $sqls = $this->getQueryLog(function () use ($user, $post1) {
            $user->hasFavorited($post1);
        });
        $this->assertEmpty($sqls->all());
    }

    public function test_eager_loading_for_errors()
    {
        $post1 = $this->CreatePost();
        $post2 = $this->CreatePost();

        $user = $this->CreateUser();

        $user->favorite($post2);

        $this->assertFalse($user->hasFavorited($post1));
        $this->assertTrue($user->hasFavorited($post2));

        $user->load('favorites');

        $this->assertFalse($user->hasFavorited($post1));
        $this->assertTrue($user->hasFavorited($post2));

        $user1 = $this->CreateUser('mike');
        $user2 = $this->CreateUser('taylor');

        $post = $this->CreatePost();

        $user2->favorite($post);

        $this->assertFalse($post->hasBeenFavoritedBy($user1));
        $this->assertTrue($post->hasBeenFavoritedBy($user2));

        $post->load('favorites');

        $this->assertFalse($post->hasBeenFavoritedBy($user1));
        $this->assertTrue($post->hasBeenFavoritedBy($user2));
    }

    public function test_favoriter_can_attach_favorite_status_to_collection()
    {
        $post1 = $this->CreatePost();
        $post2 = $this->CreatePost();
        $post3 = $this->CreatePost();

        $user = $this->CreateUser();

        $user->favorite($post1);
        $user->favorite($post2);

        $posts = Post::oldest('id')->get();
        $user->attachFavoriteStatus($posts);
        $posts = $posts->toArray();

        $this->assertTrue($posts[0]['has_favorited']);
        $this->assertTrue($posts[1]['has_favorited']);
        $this->assertFalse($posts[2]['has_favorited']);

        // paginator
        $posts = Post::oldest('id')->paginate();
        $user->attachFavoriteStatus($posts);
        $posts = $posts->toArray()['data'];

        $this->assertTrue($posts[0]['has_favorited']);
        $this->assertTrue($posts[1]['has_favorited']);
        $this->assertFalse($posts[2]['has_favorited']);

        // cursor paginator
        $posts = Post::oldest('id')->cursorPaginate();
        $user->attachFavoriteStatus($posts);
        $posts = $posts->toArray()['data'];

        $this->assertTrue($posts[0]['has_favorited']);
        $this->assertTrue($posts[1]['has_favorited']);
        $this->assertFalse($posts[2]['has_favorited']);

        // cursor lazy collection
        $posts = Post::oldest('id')->cursor();
        $posts = $user->attachFavoriteStatus($posts);
        $posts = $posts->toArray();

        $this->assertTrue($posts[0]['has_favorited']);
        $this->assertTrue($posts[1]['has_favorited']);
        $this->assertFalse($posts[2]['has_favorited']);

        // custom resolver
        $posts = [['post' => $post1], ['post' => $post2], ['post' => $post3]];

        $posts = $user->attachFavoriteStatus($posts, fn($i) => $i['post']);

        $this->assertTrue($posts[0]['post']['has_favorited']);
        $this->assertTrue($posts[1]['post']['has_favorited']);
        $this->assertFalse($posts[2]['post']['has_favorited']);
    }
}
