<?php

namespace Servnx\GetCandyFavorite\Http\Livewire\Components;

use Livewire\Component;

class Dashboard extends Component
{

    /**
     * Render the livewire component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('favorites::livewire.components.dashboard', [])->layout('adminhub::layouts.base');
    }
}
