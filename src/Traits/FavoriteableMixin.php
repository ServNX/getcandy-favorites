<?php

namespace Servnx\GetCandyFavorite\Traits;

use Illuminate\Database\Eloquent\Model;

/**
 * @property \Illuminate\Database\Eloquent\Collection $favoriters
 * @property \Illuminate\Database\Eloquent\Collection $favorites
 */
trait FavoriteableMixin
{
    public function hasFavoriter()
    {
        return function (Model $user)
        {
            return $this->hasBeenFavoritedBy($user);
        };
    }

    public function hasBeenFavoritedBy()
    {
        return function (Model $user) {
            if (is_a($user, config('auth.providers.users.model'))) {
                if ($this->relationLoaded('favoriters')) {
                    return $this->favoriters->contains($user);
                }

                return ($this->relationLoaded('favorites') ? $this->favorites : $this->favorites())
                        ->where(config('favorite.user_foreign_key'), $user->getKey())->count() > 0;
            }

            return false;
        };
    }
}
