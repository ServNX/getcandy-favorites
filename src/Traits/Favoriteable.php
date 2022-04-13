<?php

namespace Servnx\GetCandyFavorite\Traits;

use Illuminate\Database\Eloquent\Model;

/**
 * @property \Illuminate\Database\Eloquent\Collection $favoriters
 * @property \Illuminate\Database\Eloquent\Collection $favorites
 */
trait Favoriteable
{
    public function hasFavoriter(Model $user): bool
    {
        return $this->hasBeenFavoritedBy($user);
    }

    public function hasBeenFavoritedBy(Model $user): bool
    {
        if (is_a($user, config('auth.providers.users.model'))) {
            if ($this->relationLoaded('favoriters')) {
                return $this->favoriters->contains($user);
            }

            return ($this->relationLoaded('favorites') ? $this->favorites : $this->favorites())
                    ->where(config('favorite.user_foreign_key'), $user->getKey())->count() > 0;
        }

        return false;
    }

    public function favorites(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(config('favorite.favorite_model'), 'favoriteable');
    }

    public function favoriters(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        $prefix = config('getcandy.database.table_prefix');

        return $this->belongsToMany(
            config('auth.providers.users.model'),
            $prefix . config('favorite.favorites_table'),
            'favoriteable_id',
            config('favorite.user_foreign_key')
        )
            ->where('favoriteable_type', $this->getMorphClass());
    }
}
