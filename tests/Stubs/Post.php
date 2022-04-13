<?php

namespace Servnx\GetCandyFavorite\Tests\Stubs;

use Illuminate\Database\Eloquent\Model;
use Servnx\GetCandyFavorite\Traits\Favoriteable;

class Post extends Model
{
    use Favoriteable;

    protected $guarded = [];
}
