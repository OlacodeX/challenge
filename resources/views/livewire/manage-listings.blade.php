<div class="py-10 sm:py-12">
    @use('App\Enums\ListingStatus')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">{{ __('marketplace.my_listings') }}</h1>
                <p class="mt-1 text-sm text-gray-500">{{ $seller->company_name }}</p>
            </div>

            <a href="{{ route('listings.create') }}" wire:navigate class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition">
                {{ __('marketplace.create_listing') }}
            </a>
        </div>

        @if (session('status'))
            <div class="mb-6 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800">
                {{ session('status') }}
            </div>
        @endif

        @if (! $seller->isVerified())
            <div class="mb-6 rounded-lg bg-amber-50 border border-amber-200 px-4 py-3 text-sm text-amber-900">
                {{ __('marketplace.kyb_pending') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/80">
                <label for="status" class="sr-only">{{ __('marketplace.filter_by_status') }}</label>
                <select
                    id="status"
                    wire:model.live="status"
                    class="block w-full sm:w-64 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                >
                    <option value="">{{ __('marketplace.all_statuses') }}</option>
                    @foreach ($statuses as $option)
                        <option value="{{ $option->value }}">{{ $option->label() }}</option>
                    @endforeach
                </select>
            </div>

            @if ($listings->isEmpty())
                <div class="p-6 text-sm text-gray-500">
                    {{ __('marketplace.no_listings_for_filter') }}
                </div>
            @else
                <ul class="divide-y divide-gray-200">
                    @foreach ($listings as $listing)
                        <li class="p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4" wire:key="listing-{{ $listing->id }}">
                            <div>
                                <p class="font-medium text-gray-900">{{ $listing->title }}</p>
                                <p class="mt-1 text-sm text-gray-500">
                                    {{ $listing->status->label() }}
                                    · {{ $listing->city }}, {{ strtoupper($listing->country->value) }}
                                </p>
                            </div>

                            <div class="flex items-center gap-3">
                                @switch($listing->status)
                                    @case(ListingStatus::DRAFT)
                                        <button
                                            type="button"
                                            wire:click="publish({{ $listing->id }})"
                                            wire:confirm="{{ __('marketplace.publish_confirm') }}"
                                            class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition"
                                        >
                                            {{ __('marketplace.publish') }}
                                        </button>
                                        @break

                                    @case(ListingStatus::PUBLISHED)
                                        @if ($listing->isWithinPublicationWindow())
                                            <a href="{{ route('listings.show', $listing) }}" wire:navigate class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                                                {{ __('marketplace.view') }}
                                            </a>
                                        @endif
                                        @break
                                @endswitch
                            </div>
                        </li>
                    @endforeach
                </ul>

                @if ($listings->hasPages())
                    <nav class="px-5 py-4 border-t border-gray-100 flex items-center justify-between gap-4" aria-label="Pagination">
                        <button
                            type="button"
                            wire:click="$set('page', {{ $listings->currentPage() - 1 }})"
                            @disabled($listings->onFirstPage())
                            class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-semibold transition
                                {{ $listings->onFirstPage()
                                    ? 'cursor-not-allowed bg-gray-100 text-gray-400'
                                    : 'bg-white text-gray-700 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 hover:text-indigo-600 hover:ring-indigo-300' }}"
                        >
                            {{ __('marketplace.previous') }}
                        </button>

                        <span class="text-sm text-gray-500">
                            {{ __('marketplace.page_of', ['current' => $listings->currentPage(), 'total' => $listings->lastPage()]) }}
                        </span>

                        <button
                            type="button"
                            wire:click="$set('page', {{ $listings->currentPage() + 1 }})"
                            @disabled(! $listings->hasMorePages())
                            class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-semibold transition
                                {{ ! $listings->hasMorePages()
                                    ? 'cursor-not-allowed bg-gray-100 text-gray-400'
                                    : 'bg-indigo-600 text-white shadow-sm hover:bg-indigo-500' }}"
                        >
                            {{ __('marketplace.next') }}
                        </button>
                    </nav>
                @endif
            @endif
        </div>
    </div>
</div>
