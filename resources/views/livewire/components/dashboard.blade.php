<div>
    <div class="flex justify-between px-4 mx-auto max-w-7xl sm:px-6 md:px-8">
        <h1 class="text-2xl font-semibold text-gray-900">Favorites Dashboard</h1>
        <div class="flex items-center space-x-4">
            <x-hub::input.datepicker wire:model="range.from" />
            <span class="text-xs font-medium text-gray-500 uppercase">to</span>
            <x-hub::input.datepicker wire:model="range.to" />
        </div>
    </div>
    <div class="px-4 mx-auto mt-8 max-w-7xl sm:px-6 md:px-8">
        <div class="flex flex-row gap-x-8">
            <div class="basis-1/4">
                <div class="flex items-center h-24 p-4 bg-white rounded-lg">
                    <div class="flex items-center justify-center w-12 h-12 ml-2 bg-blue-200 rounded-full">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                  d="M15.8498 2.50071C16.4808 2.50071 17.1108 2.58971 17.7098 2.79071C21.4008 3.99071 22.7308 8.04071 21.6198 11.5807C20.9898 13.3897 19.9598 15.0407 18.6108 16.3897C16.6798 18.2597 14.5608 19.9197 12.2798 21.3497L12.0298 21.5007L11.7698 21.3397C9.4808 19.9197 7.3498 18.2597 5.4008 16.3797C4.0608 15.0307 3.0298 13.3897 2.3898 11.5807C1.2598 8.04071 2.5898 3.99071 6.3208 2.76971C6.6108 2.66971 6.9098 2.59971 7.2098 2.56071H7.3298C7.6108 2.51971 7.8898 2.50071 8.1698 2.50071H8.2798C8.9098 2.51971 9.5198 2.62971 10.1108 2.83071H10.1698C10.2098 2.84971 10.2398 2.87071 10.2598 2.88971C10.4808 2.96071 10.6898 3.04071 10.8898 3.15071L11.2698 3.32071C11.3616 3.36968 11.4647 3.44451 11.5538 3.50918C11.6102 3.55015 11.661 3.58705 11.6998 3.61071C11.7161 3.62034 11.7327 3.63002 11.7494 3.63978C11.8352 3.68983 11.9245 3.74197 11.9998 3.79971C13.1108 2.95071 14.4598 2.49071 15.8498 2.50071ZM18.5098 9.70071C18.9198 9.68971 19.2698 9.36071 19.2998 8.93971V8.82071C19.3298 7.41971 18.4808 6.15071 17.1898 5.66071C16.7798 5.51971 16.3298 5.74071 16.1798 6.16071C16.0398 6.58071 16.2598 7.04071 16.6798 7.18971C17.3208 7.42971 17.7498 8.06071 17.7498 8.75971V8.79071C17.7308 9.01971 17.7998 9.24071 17.9398 9.41071C18.0798 9.58071 18.2898 9.67971 18.5098 9.70071Z"
                                  fill="#5B93FF" />
                        </svg>
                    </div>
                    <div class="flex items-center ml-4">
                        <div>
                            <strong class="text-lg font-bold">{{ $this->newFavoritesCount }}</strong>
                            <span class="block text-xs">New Favorites</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="basis-1/4">

            </div>
            <div class="basis-1/4">

            </div>
            <div class="basis-1/4">

            </div>
        </div>
    </div>
    <div class="px-4 mx-auto mt-8 max-w-7xl sm:px-6 md:px-8">
        <div class="p-8 bg-white rounded-lg h-96">
            <h3 class="text-lg font-semibold text-gray-900">Top Favorited Products</h3>

            @foreach($this->topFavoritedProducts as $product)
                <div class="relative flex items-center py-8 space-x-3 bg-white border-b border-slate-100">
                    <div class="flex-shrink-0">
                        @if($thumbnail = $product->variants->first()->getThumbnail())
                            <img src="{{ $thumbnail }}" class="w-24 h-24 rounded-lg" alt="" />
                        @else
                            <x-hub::icon ref="photograph" class="w-24 h-24 text-gray-200 rounded-lg" />
                        @endif

                    </div>
                    <div class="flex-1 min-w-0">
                        <a href="#" class="focus:outline-none">
                            <span class="absolute inset-0" aria-hidden="true"></span>
                            <p class="text-sm font-medium text-gray-900">
                                {{ $product->variants->first()->getDescription() }}
                                <span class="block text-sm">{{ $product->variants->first()->getIdentifier() }}</span>
                            </p>
                            <p class="text-sm text-gray-500 truncate">
                                {{ $product->favoriters_count }} Favorites
                            </p>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
