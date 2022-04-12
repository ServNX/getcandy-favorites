<?php

namespace Servnx\GetCandyFavorite\Tests\Stubs;

use Illuminate\Database\Eloquent\Model;
use Servnx\GetCandyFavorite\Traits\Favoriter;

class User extends Model
{
    use Favoriter;

    protected $guarded = [];
}
