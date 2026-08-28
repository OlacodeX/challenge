<div class="py-10 sm:py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="{{ route('listings.manage') }}" wire:navigate class="inline-flex items-center gap-1.5 text-sm font-medium text-indigo-600 hover:text-indigo-800 transition mb-6">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </svg>
            Back to my listings
        </a>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
            <div class="h-2 bg-gradient-to-r from-indigo-500 to-indigo-700"></div>

            <div class="p-6 sm:p-8">
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Create listing</h1>
                <p class="mt-1 text-sm text-gray-500">Save a draft listing. Publishing requires verified seller status.</p>

                <form wire:submit.prevent="save" class="mt-8 space-y-8">
                    <section class="space-y-4">
                        <h2 class="text-base font-semibold text-gray-900">Listing details</h2>

                        <div>
                            <x-input-label for="title" value="Title" />
                            <x-text-input wire:model="title" id="title" class="block mt-1 w-full" type="text" required />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="description" value="Description" />
                            <textarea
                                wire:model="description"
                                id="description"
                                rows="5"
                                required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                            ></textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="category" value="Category" />
                                <select wire:model="category" id="category" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    <option value="">Select category</option>
                                    @foreach ($categories as $option)
                                        <option value="{{ $option->value }}">{{ $option->label() }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('category')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="price" value="Price" />
                                <x-text-input wire:model="price" id="price" class="block mt-1 w-full" type="number" step="0.01" min="0.01" required />
                                <x-input-error :messages="$errors->get('price')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <x-input-label for="currency" value="Currency" />
                                <select wire:model="currency" id="currency" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    @foreach ($currencies as $option)
                                        <option value="{{ $option->value }}">{{ $option->label() }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('currency')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="country" value="Country" />
                                <select wire:model="country" id="country" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    @foreach ($countries as $option)
                                        <option value="{{ $option->value }}">{{ $option->label() }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('country')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="city" value="City" />
                                <x-text-input wire:model="city" id="city" class="block mt-1 w-full" type="text" required />
                                <x-input-error :messages="$errors->get('city')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="date_online" value="Available from" />
                                <x-text-input wire:model="date_online" id="date_online" class="block mt-1 w-full" type="date" required />
                                <x-input-error :messages="$errors->get('date_online')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="date_offline" value="Available until" />
                                <x-text-input wire:model="date_offline" id="date_offline" class="block mt-1 w-full" type="date" required />
                                <x-input-error :messages="$errors->get('date_offline')" class="mt-2" />
                            </div>
                        </div>
                    </section>

                    <section class="rounded-xl border border-gray-200 bg-gray-50/50 p-5 sm:p-6">
                        <h2 class="text-base font-semibold text-gray-900">Seller on this listing</h2>
                        <p class="mt-1 text-sm text-gray-500">Contact details come from your seller profile and are revealed to buyers separately.</p>

                        <dl class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                            <div>
                                <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Company</dt>
                                <dd class="mt-1 font-semibold text-gray-900">{{ $seller->company_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Contact email</dt>
                                <dd class="mt-1 font-semibold text-gray-900">{{ $seller->contact_email }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Contact phone</dt>
                                <dd class="mt-1 font-semibold text-gray-900">{{ $seller->contact_phone }}</dd>
                            </div>
                            @if (filled($seller->getAttributes()['vat_number'] ?? null))
                                <div>
                                    <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">VAT number</dt>
                                    <dd class="mt-1 font-semibold text-gray-900">{{ $seller->vat_number }}</dd>
                                </div>
                            @endif
                            @if (filled($seller->getAttributes()['registration_number'] ?? null))
                                <div>
                                    <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Registration number</dt>
                                    <dd class="mt-1 font-semibold text-gray-900">{{ $seller->registration_number }}</dd>
                                </div>
                            @endif
                        </dl>
                    </section>

                    <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 border-t border-gray-100 pt-6">
                        <x-primary-button class="w-full sm:w-auto">
                            Save draft
                        </x-primary-button>

                        <button
                            type="button"
                            wire:click="saveAndPublish"
                            class="inline-flex items-center justify-center rounded-md bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 transition w-full sm:w-auto"
                        >
                            Save and publish
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
