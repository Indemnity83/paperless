<div class="relative z-10 flex-shrink-0 flex h-16 bg-white border-b border-gray-200">
    <!-- Sidebar toggle, controls the 'sidebarOpen' sidebar state. -->
    <button class="px-4 border-r border-gray-200 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-purple-500 lg:hidden">
        <span class="sr-only">Open sidebar</span>
        <!-- Heroicon name: menu-alt-1 -->
        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" />
        </svg>
    </button>
    <div class="flex-1 flex justify-between px-4 sm:px-6 lg:px-8">
        <div class="flex-1 flex">
            <form class="w-full flex md:ml-0" action="{{ route('documents.search') }}" method="GET">
                <label for="search_field" class="sr-only">Search</label>
                <div class="relative w-full text-gray-400 focus-within:text-gray-600">
                    <div class="absolute inset-y-0 left-0 flex items-center pointer-events-none">
                        <!-- Heroicon name: search -->
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input id="search_field" name="q" class="block w-full h-full pl-8 pr-3 py-2 border-transparent text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-0 focus:border-transparent focus:placeholder-gray-400 sm:text-sm" placeholder="Search" type="search" value="{{ request()->get('q') }}">
                </div>
            </form>
        </div>
        <div class="flex items-center">
            <!-- Profile dropdown -->
            <div class="ml-3 relative">
                <div>
                    <button class="max-w-xs bg-white flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500" id="user-menu" aria-haspopup="true">
                        <span class="sr-only">Open user menu</span>
                        <img class="h-8 w-8 rounded-full" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}">
                    </button>
                </div>
                <!--
                  Profile dropdown panel, show/hide based on dropdown state.

                  Entering: "transition ease-out duration-100"
                    From: "transform opacity-0 scale-95"
                    To: "transform opacity-100 scale-100"
                  Leaving: "transition ease-in duration-75"
                    From: "transform opacity-100 scale-100"
                    To: "transform opacity-0 scale-95"
                -->
                <div class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-200" role="menu" aria-orientation="vertical" aria-labelledby="user-menu">
                    <div class="py-1" role="none">
                        <x-jet-dropdown-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                            {{ __('Profile') }}
                        </x-jet-dropdown-link>

                        @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                            <x-jet-dropdown-link href="{{ route('api-tokens.index') }}">
                                {{ __('API Tokens') }}
                            </x-jet-dropdown-link>
                        @endif

                    </div>
                    <div class="py-1" role="none">
                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-jet-dropdown-link href="{{ route('logout') }}"
                                     onclick="event.preventDefault();
                                            this.closest('form').submit();">
                                {{ __('Logout') }}
                            </x-jet-dropdown-link>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
