## GetCandy 2 Favorites Addon

Favorites addon for GetCandy 2 Application.

## Installing

```
composer require servnx/getcandy-favorites
```

### The Favoriter
The *"favoriter"* is the *"user"* in which is favoriting objects.

Add the **Favoriter Trait** to your desired User model.
```php
use Servnx\GetCandyFavorite\Traits\Favoriter;

class User extends Authenticatable
{
    use HasFactory,
        GetCandyUser,
        Favoriter,
        Billable,
        Notifiable;
       
    ...
}
```

### Publishing Configurations & Migrations

```php
php artisan vendor:publish --tag="getcandy-favorites"
```

## Adding screens to GetCandy Admin Hub
If you have the admin hub installed, you can enable the screens by publishing the 
configs demonstrated above and setting the `hub` value to `true` (false by default).
```php
/*
* If you have GetCandy Admin Hub installed set this to true (default is false).
*/
'hub' => true,
```

## Usage

### Supported GetCandy Favoriteable Models.
```php 
GetCandy\Models\Product::class
... more to come ...
```

### Adding favoritebale to your own Models.
```php 
use Illuminate\Database\Eloquent\Model;
use Servnx\GetCandyFavorite\Traits\Favoriteable;

class Post extends Model
{
    use Favoriteable;
    
    ...
}
```

### API

```php
$user = User::find(1);
$product = Product::find(2);

$user->favorite($product);
$user->unfavorite($product);
$user->toggleFavorite($product);
$user->getFavoriteItems(Product::class)

$user->hasFavorited($product);
$product->hasBeenFavoritedBy($user);
```

#### Get Favoriters example:

```php
foreach($product->favoriters as $user) {
    ...
}
```

#### Get Favorite Model from User.
This will return a `Illuminate\Database\Eloquent\Builder` instance.

```php
$favoriteItems = $user->getFavoriteItems(Product::class);

// more examples
$favoriteItems->get();
$favoriteItems->paginate();
$favoriteItems->find(1)->get();
```

### Aggregations

```php
// all favorites by this user
$user->favorites()->count();

// how many Products has this user favorited ?
$user->favorites()->withType(Product::class)->count();

// how many users favorited this product ?
$product->favoriters()->count();
```

List with `*_count` attribute:

```php
$users = User::withCount('favorites')->get();

foreach($users as $user) {
    echo $user->favorites_count;
}


// for Favoriteable models:
$products = Product::withCount('favoriters')->get();

foreach($products as $product) {
    echo $product->favoriters_count;
}
```

### Attach user favorite status to favoriteable collection

You can use `Favoriter::attachFavoriteStatus($favoriteables)` to attach the user favorite status, it will set `has_favorited` attribute to each model of `$favoriteables`:

#### For `Models`

```php
$product = Product::find(1);

$product = $user->attachFavoriteStatus($product);

$product->toArray();

// example result
[
    "id" => 1
    ...
    "has_favorited" => true
],
```

#### For `Collection | Paginator | LengthAwarePaginator | array`:

```php
$products = Product::oldest('id')->get();

$products = $user->attachFavoriteStatus($products);

$products->toArray();

// example result
[
  [
    "id" => 1
    ...
    "has_favorited" => true
  ],
  [
    "id" => 2
    ...
    "has_favorited" => false
  ],
  [
    "id" => 3
    ...
    "has_favorited" => true
  ],
]
```

### N+1 issue

To avoid the N+1 issue, you can use eager loading to reduce this operation to just 2 queries. 
When querying, you may specify which relationships should be eager loaded using the `with` method:

```php
// Favoriter
$users = User::with('favorites')->get();

// Favoriteable
$products = Product::with('favorites')->get();
$products = Product::with('favoriters')->get();
```

### Events

| **Event**                                     | **Description**                             |
| --------------------------------------------- | ------------------------------------------- |
| `Servnx\GetCandyFavorite\Events\Favorited`   | Triggered when the relationship is created. |
| `Servnx\GetCandyFavorite\Events\Unfavorited` | Triggered when the relationship is deleted. |

## License

MIT

Credits to [Overtrue Laravel Favorites](https://github.com/overtrue/laravel-favorite).
