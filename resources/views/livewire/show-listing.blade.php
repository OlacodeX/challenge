<div class="py-10 sm:py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="{{ route('home') }}" wire:navigate class="inline-flex items-center gap-1.5 text-sm font-medium {{ $listing->category->linkClasses() }} transition mb-6">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </svg>
            {{ __('marketplace.back_to_listings') }}
        </a>

        <article class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
            <div class="h-2 bg-gradient-to-r {{ $listing->category->accentGradient() }}"></div>

            <div class="p-6 sm:p-8">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset {{ $listing->category->badgeClasses() }}">
                            {{ $listing->category->label() }}
                        </span>
                        <h1 class="mt-3 text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">
                            {{ $listing->title }}
                        </h1>
                        <p class="mt-2 text-sm text-gray-500">
                            {{ __('marketplace.listed_by', ['company' => $listing->seller->company_name]) }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-3xl font-bold text-gray-900 tracking-tight">
                            {{ Number::currency($listing->price, $listing->currency->label(), app()->getLocale()) }}
                        </p>
                        <p class="mt-1 text-xs text-gray-400 uppercase tracking-wide">{{ $listing->currency->label() }}</p>
                    </div>
                </div>

                <dl class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="rounded-xl bg-gray-50 px-4 py-3 ring-1 ring-gray-100">
                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ __('marketplace.location') }}</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $listing->city }}, {{ $listing->country->label() }}</dd>
                    </div>
                    <div class="rounded-xl bg-gray-50 px-4 py-3 ring-1 ring-gray-100">
                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ __('marketplace.available_from') }}</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $listing->date_online->translatedFormat('M j, Y') }}</dd>
                    </div>
                    <div class="rounded-xl bg-gray-50 px-4 py-3 ring-1 ring-gray-100">
                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ __('marketplace.available_until') }}</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $listing->date_offline->translatedFormat('M j, Y') }}</dd>
                    </div>
                </dl>

                <div class="mt-8 prose prose-sm max-w-none text-gray-600">
                    <h2 class="text-base font-semibold text-gray-900 not-prose">{{ __('marketplace.description') }}</h2>
                    <p class="mt-2 whitespace-pre-line">{{ $listing->description }}</p>
                </div>

                <div class="mt-8 rounded-xl border border-gray-200 bg-gray-50/50 p-5 sm:p-6">
                    <h2 class="text-base font-semibold text-gray-900">{{ __('marketplace.seller_contact') }}</h2>
                    <p class="mt-1 text-sm text-gray-500">{{ __('marketplace.contact_protected') }}</p>

                    @if ($contactRevealed)
                        <dl class="mt-4 space-y-3">
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ __('marketplace.email') }}</dt>
                                <dd class="mt-1">
                                    <a href="mailto:{{ $revealedEmail }}" class="text-sm font-semibold {{ $listing->category->linkClasses() }}">
                                        {{ $revealedEmail }}
                                    </a>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ __('marketplace.phone') }}</dt>
                                <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $revealedPhone }}</dd>
                            </div>
                        </dl>
                    @else
                        <div class="mt-4">
                            @auth
                                <button
                                    type="button"
                                    wire:click="revealContact"
                                    wire:loading.attr="disabled"
                                    class="inline-flex items-center gap-2 rounded-lg px-4 py-2.5 text-sm font-semibold text-white shadow-sm disabled:opacity-50 transition {{ $listing->category->primaryButtonClasses() }}"
                                >
                                    <span wire:loading.remove wire:target="revealContact">{{ __('marketplace.reveal_contact') }}</span>
                                    <span wire:loading wire:target="revealContact">{{ __('marketplace.revealing') }}</span>
                                </button>
                            @else
                                <a
                                    href="{{ route('login') }}"
                                    wire:navigate
                                    class="inline-flex items-center gap-2 rounded-lg px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition {{ $listing->category->primaryButtonClasses() }}"
                                >
                                    {{ __('marketplace.login_to_reveal') }}
                                </a>
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </article>
    </div>
</div>
