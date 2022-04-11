<?php

namespace Servnx\GetCandyFavorite\Http\Livewire\Components;

use GetCandy\Models\Product;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Servnx\GetCandyFavorite\Favorite;

class Dashboard extends Component
{
    /**
     * The date range for the dashboard reports.
     *
     * @var array
     */
    public array $range = [
        'from' => null,
        'to'   => null,
    ];

    /**
     * {@inheritDoc}
     */
    protected $queryString = ['range'];

    public function mount()
    {
        $this->range['from'] = $this->range['from'] ?? now()->startOfWeek()->format('Y-m-d');
        $this->range['to'] = $this->range['too'] ?? now()->endOfWeek()->format('Y-m-d');
    }

    public function rules()
    {
        return [
            'range.from' => 'date',
            'range.to'   => 'date,after:range.from',
        ];
    }

    /**
     * Get the computed property for new products count.
     *
     * @return int
     */
    public function getNewFavoritesCountProperty(): int
    {
        return Favorite::whereBetween('created_at', [
            now()->parse($this->range['from']),
            now()->parse($this->range['to']),
        ])->count();
    }

    /**
     * Return the computed property for top favorited products.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getTopFavoritedProductsProperty()
    {
        return Product::with(['variants'])
            ->withCount('favoriters')
            ->orderBy('favoriters_count', 'desc')
            ->limit(10)
            ->get();
    }

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
