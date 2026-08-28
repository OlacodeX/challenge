<div class="py-10 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-600 via-indigo-700 to-indigo-900 px-6 py-10 sm:px-10 sm:py-12 shadow-lg">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=%2260%22 height=%2260%22 viewBox=%220 0 60 60%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cg fill=%22none%22 fill-rule=%22evenodd%22%3E%3Cg fill=%22%23ffffff%22 fill-opacity=%220.05%22%3E%3Cpath d=%22M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z/%22/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-60"></div>
            <div class="relative">
                <p class="text-indigo-200 text-sm font-medium tracking-wide uppercase">B2B Marketplace</p>
                <h1 class="mt-2 text-3xl sm:text-4xl font-bold text-white tracking-tight">Find business assets</h1>
                <p class="mt-3 max-w-2xl text-indigo-100 text-base sm:text-lg">
                    Machinery, vehicles, commercial property, and intangibles across France, Belgium, and Luxembourg.
                </p>
            </div>
        </div>

        <div class="mt-8 lg:grid lg:grid-cols-12 lg:gap-8">
            <aside class="lg:col-span-3 mb-8 lg:mb-0">
                <div class="lg:sticky lg:top-6 bg-white rounded-xl shadow-sm border border-gray-200/80 overflow-hidden">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 bg-gray-50/80">
                        <h2 class="text-sm font-semibold text-gray-900 uppercase tracking-wide">Filters</h2>
                        @if ($title || $category || $country || $minPrice || $maxPrice || $sort !== 'newest')
                            <button
                                type="button"
                                wire:click="clearFilters"
                                class="text-xs font-medium text-indigo-600 hover:text-indigo-800 transition"
                            >
                                Clear all
                            </button>
                        @endif
                    </div>

                    <div class="p-5 space-y-5">
                        <div>
                            <x-input-label for="title" value="Search" />
                            <div class="relative mt-1">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                    </svg>
                                </div>
                                <x-text-input
                                    id="title"
                                    type="search"
                                    wire:model.live.debounce.300ms="title"
                                    class="pl-10 block w-full text-sm"
                                    placeholder="Search by title…"
                                />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="category" value="Category" />
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
                            <x-input-label for="country" value="Country" />
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

                        <div>
                            <x-input-label value="Price range (EUR)" />
                            <div class="mt-1 grid grid-cols-2 gap-3">
                                <x-text-input
                                    id="minPrice"
                                    type="number"
                                    min="0"
                                    step="0.01"
                                    wire:model.live.debounce.500ms="minPrice"
                                    class="block w-full text-sm"
                                    placeholder="Min"
                                />
                                <x-text-input
                                    id="maxPrice"
                                    type="number"
                                    min="0"
                                    step="0.01"
                                    wire:model.live.debounce.500ms="maxPrice"
                                    class="block w-full text-sm"
                                    placeholder="Max"
                                />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="sort" value="Sort by" />
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
                </div>
            </aside>

            <section class="lg:col-span-9">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
                    <div>
                        <p class="text-sm text-gray-500">
                            @if ($listings->total() === 1)
                                <span class="font-semibold text-gray-900">1</span> listing found
                            @else
                                <span class="font-semibold text-gray-900">{{ number_format($listings->total()) }}</span> listings found
                            @endif
                        </p>
                        @if ($listings->total() > 0)
                            <p class="text-xs text-gray-400 mt-0.5">
                                Showing {{ $listings->firstItem() }}–{{ $listings->lastItem() }}
                            </p>
                        @endif
                    </div>

                    <div wire:loading.delay class="flex items-center gap-2 text-sm text-indigo-600">
                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Updating results…
                    </div>
                </div>

                <div wire:loading.delay.class="opacity-60 pointer-events-none" class="transition-opacity duration-200">
                    @if ($listings->count() === 0)
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm px-6 py-16 text-center">
                            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-gray-100">
                                <svg class="h-7 w-7 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                </svg>
                            </div>
                            <h3 class="mt-4 text-base font-semibold text-gray-900">No listings match your filters</h3>
                            <p class="mt-2 text-sm text-gray-500 max-w-sm mx-auto">Try adjusting your search criteria or clearing filters to see more results.</p>
                            @if ($title || $category || $country || $minPrice || $maxPrice || $sort !== 'newest')
                                <button
                                    type="button"
                                    wire:click="clearFilters"
                                    class="mt-6 inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition"
                                >
                                    Clear filters
                                </button>
                            @endif
                        </div>
                    @else
                        <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                            @foreach ($listings as $listing)
                                <article class="group bg-white rounded-xl border border-gray-200/80 shadow-sm hover:shadow-md {{ $listing->category->cardBorderHoverClass() }} transition-all duration-200 overflow-hidden flex flex-col">
                                    <div class="h-2 bg-gradient-to-r {{ $listing->category->accentGradient() }}"></div>
                                    <div class="p-5 flex-1 flex flex-col">
                                        <div class="flex items-start justify-between gap-2">
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset {{ $listing->category->badgeClasses() }}">
                                                {{ $listing->category->label() }}
                                            </span>
                                            <span class="shrink-0 text-xs font-medium text-gray-400 uppercase tracking-wide">
                                                {{ $listing->currency->label() }}
                                            </span>
                                        </div>

                                        <h2 class="mt-3 text-base font-semibold text-gray-900 line-clamp-2 {{ $listing->category->cardTitleHoverClass() }} transition-colors">
                                            <a href="{{ route('listings.show', $listing) }}" wire:navigate>
                                                {{ $listing->title }}
                                            </a>
                                        </h2>

                                        <div class="mt-2 flex items-center gap-1.5 text-sm text-gray-500">
                                            <svg class="h-4 w-4 shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                            </svg>
                                            <span>{{ $listing->city }}, {{ $listing->country->label() }}</span>
                                        </div>

                                        <div class="mt-auto pt-4 flex items-end justify-between gap-3 border-t border-gray-100">
                                            <div>
                                                <p class="text-xl font-bold text-gray-900 tracking-tight">
                                                    {{ Number::currency($listing->price, $listing->currency->label()) }}
                                                </p>
                                                <p class="mt-0.5 text-xs text-gray-500 truncate max-w-[10rem]">
                                                    {{ $listing->seller->company_name }}
                                                </p>
                                            </div>
                                            <a
                                                href="{{ route('listings.show', $listing) }}"
                                                wire:navigate
                                                class="shrink-0 inline-flex items-center gap-1 rounded-lg bg-gray-50 px-3 py-1.5 text-xs font-semibold text-gray-700 ring-1 ring-inset ring-gray-200 {{ $listing->category->cardActionButtonHoverClass() }} transition"
                                            >
                                                View
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        @if ($listings->hasPages())
                            <nav class="mt-8 flex items-center justify-between gap-4 border-t border-gray-200 pt-6" aria-label="Pagination">
                                <button
                                    type="button"
                                    wire:click="$set('page', {{ $listings->currentPage() - 1 }})"
                                    @disabled($listings->onFirstPage())
                                    class="inline-flex items-center gap-2 rounded-lg px-4 py-2.5 text-sm font-semibold transition
                                        {{ $listings->onFirstPage()
                                            ? 'cursor-not-allowed bg-gray-100 text-gray-400'
                                            : 'bg-white text-gray-700 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 hover:text-indigo-600 hover:ring-indigo-300' }}"
                                >
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                                    </svg>
                                    Previous
                                </button>

                                <span class="text-sm text-gray-500">
                                    Page <span class="font-semibold text-gray-900">{{ $listings->currentPage() }}</span>
                                    of <span class="font-semibold text-gray-900">{{ $listings->lastPage() }}</span>
                                </span>

                                <button
                                    type="button"
                                    wire:click="$set('page', {{ $listings->currentPage() + 1 }})"
                                    @disabled(! $listings->hasMorePages())
                                    class="inline-flex items-center gap-2 rounded-lg px-4 py-2.5 text-sm font-semibold transition
                                        {{ ! $listings->hasMorePages()
                                            ? 'cursor-not-allowed bg-gray-100 text-gray-400'
                                            : 'bg-indigo-600 text-white shadow-sm hover:bg-indigo-500' }}"
                                >
                                    Next
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                    </svg>
                                </button>
                            </nav>
                        @endif
                    @endif
                </div>
            </section>
        </div>
    </div>
</div>
