<x-jet-action-section>
    <x-slot name="title">
        {{ __('Tools') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Access additional tools for debugging and troubleshooting.') }}
    </x-slot>

    <x-slot name="content">
        <!-- This example requires Tailwind CSS v2.0+ -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="relative rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm flex items-center space-x-3 hover:border-gray-400 focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                <div class="flex-1 min-w-0">
                    <a href="{{ route('horizon.index') }}" class="focus:outline-none">
                        <span class="absolute inset-0" aria-hidden="true"></span>
                        <p class="text-sm font-medium text-gray-900">
                            Laravel Horizon
                        </p>
                        <p class="text-sm text-gray-500 truncate">
                            Queue management
                        </p>
                    </a>
                </div>
            </div>

        </div>


    </x-slot>
</x-jet-action-section>
