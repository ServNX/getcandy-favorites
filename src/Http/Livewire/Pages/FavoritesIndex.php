<?php

namespace Servnx\GetCandyFavorite\Http\Livewire\Pages;

use Livewire\Component;

class FavoritesIndex extends Component
{
    public function render()
    {
        return view('favorites::livewire.index')->layout('adminhub::layouts.app', [
            'title' => 'Favorites',
        ]);
    }
}
