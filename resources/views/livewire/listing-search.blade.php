<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-gray-900">Browse listings</h1>
        <p class="mt-1 text-sm text-gray-600">B2B assets across France, Belgium, and Luxembourg.</p>
    </div>

    <div class="lg:grid lg:grid-cols-4 lg:gap-8">
        <aside class="mb-6 lg:mb-0">
            <div class="bg-white rounded-lg border border-gray-200 p-4 space-y-4">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                    <input
                        id="title"
                        type="search"
                        wire:model.live.debounce.300ms="title"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                        placeholder="Search by title"
                    >
                </div>

                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                    <select
                        id="category"
                        wire:model.live="category"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                    >
                        <option value="">All categories</option>
                        @foreach ($categories as $option)
                            <option value="{{ $option->value }}">
                                {{ $option->label() }}
                                @if(isset($filterOptionCounts['categories'][$option->value]))
                                    ({{ $filterOptionCounts['categories'][$option->value] }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                    <select
                        id="country"
                        wire:model.live="country"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                    >
                        <option value="">All countries</option>
                        @foreach ($countries as $option)
                            <option value="{{ $option->value }}">
                                {{ $option->label() }}
                                @if(isset($filterOptionCounts['countries'][$option->value]))
                                    ({{ $filterOptionCounts['countries'][$option->value] }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="minPrice" class="block text-sm font-medium text-gray-700">Min price</label>
                        <input
                            id="minPrice"
                            type="number"
                            min="0"
                            step="0.01"
                            wire:model.live.debounce.500ms="minPrice"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                            placeholder="0"
                        >
                    </div>
                    <div>
                        <label for="maxPrice" class="block text-sm font-medium text-gray-700">Max price</label>
                        <input
                            id="maxPrice"
                            type="number"
                            min="0"
                            step="0.01"
                            wire:model.live.debounce.500ms="maxPrice"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                            placeholder="Any"
                        >
                    </div>
                </div>

                <div>
                    <label for="sort" class="block text-sm font-medium text-gray-700">Sort by</label>
                    <select
                        id="sort"
                        wire:model.live="sort"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                    >
                        @foreach ($sortOptions as $option)
                            <option value="{{ $option->value }}">{{ $option->label() }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </aside>

        <section class="lg:col-span-3">
            @if ($listings->count() === 0)
                <div class="bg-white rounded-lg border border-gray-200 p-8 text-center text-gray-600">
                    No listings match your filters.
                </div>
            @else
                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach ($listings as $listing)
                        <article class="bg-white rounded-lg border border-gray-200 overflow-hidden flex flex-col">
                            <div class="p-4 flex-1">
                                <p class="text-xs font-medium uppercase tracking-wide text-indigo-600">
                                    {{ $listing->category->label() }}
                                </p>
                                <h2 class="mt-2 text-base font-semibold text-gray-900 line-clamp-2">
                                    <a href="{{ url('/listings/'.$listing->slug) }}" class="hover:text-indigo-600" wire:navigate>
                                        {{ $listing->title }}
                                    </a>
                                </h2>
                                <p class="mt-2 text-sm text-gray-600">
                                    {{ $listing->city }}, {{ $listing->country->label() }}
                                </p>
                                <p class="mt-3 text-lg font-semibold text-gray-900">
                                    {{ Number::currency($listing->price, $listing->currency->label()) }}
                                </p>
                                <p class="mt-1 text-xs text-gray-500">
                                    {{ $listing->seller->company_name }}
                                </p>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $listings->links() }}
                </div>
            @endif
        </section>
    </div>
    </div>
</div>
