<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="apple-mobile-web-app-capable" content="yes">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <title>Paperless | {{ request()->has('q') ? 'Search results for ' . request('q') : 'All Files' }}</title>

    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Icons -->
    <link rel="shortcut icon" href="favicon.ico">
    <link rel="apple-touch-icon" type="image/png" href="/img/apple-touch-icon-57x57-precomposed.png" sizes="57x57" >
    <link rel="apple-touch-icon" type="image/png" href="/img/apple-touch-icon-60x60-precomposed.png" sizes="60x60" >
    <link rel="apple-touch-icon" type="image/png" href="/img/apple-touch-icon-72x72-precomposed.png" sizes="72x72" >
    <link rel="apple-touch-icon" type="image/png" href="/img/apple-touch-icon-76x76-precomposed.png" sizes="76x76" >
    <link rel="apple-touch-icon" type="image/png" href="/img/apple-touch-icon-114x114-precomposed.png" sizes="114x114" >
    <link rel="apple-touch-icon" type="image/png" href="/img/apple-touch-icon-120x120-precomposed.png" sizes="120x120" >
    <link rel="apple-touch-icon" type="image/png" href="/img/apple-touch-icon-144x144-precomposed.png" sizes="144x144" >
    <link rel="apple-touch-icon" type="image/png" href="/img/apple-touch-icon-152x152-precomposed.png" izes="152x152" >
    <link rel="apple-touch-icon" type="image/png" href="/img/apple-touch-icon-180x180-precomposed.png" sizes="180x180" >
    <link rel="icon" type="image/png" href="/img/favicon-16x16.png" sizes="16x16" >
    <link rel="icon" type="image/png" href="/img/favicon-32x32.png" sizes="32x32" >
    <link rel="icon" type="image/png" href="/img/favicon-72x72.png" sizes="72x72" >
    <link rel="icon" type="image/png" href="/img/favicon-96x96.png" sizes="96x96" >
    <link rel="icon" type="image/png" href="/img/favicon-128x128.png" sizes="128x128" >
    <link rel="icon" type="image/png" href="/img/favicon-196x196.png" sizes="196x196" >
    <link rel="manifest" href="site.webmanifest">
    <meta name="msapplication-TileColor" content="#2b5797">
    <meta name="msapplication-config" content="browserconfig.xml">
    <meta name="theme-color" content="#ffffff">
    <!-- <link rel='mask-icon' href='safari-pinned-tab.svg' color='#5bbad5'>  you'll have to put your svg here -->
</head>
<body class="h-full w-full m-0">


<div class="h-screen w-full flex overflow-hidden bg-gray-100" x-data="{ openMenu: false }">
    <div class="lg:hidden" x-show="openMenu">
        <div class="fixed inset-0 flex z-40">
            <div class="fixed inset-0"
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
            >
                <div class="absolute inset-0 bg-gray-600 opacity-75" aria-hidden="true"></div>
            </div>
            <div class="relative flex-1 flex flex-col max-w-xs w-full pt-5 pb-4 bg-chocolate-700"
                 x-transition:enter="transition-opacity ease-linear duration-300 transform"
                 x-transition:enter-start="-translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition-opacity ease-linear duration-300 transform"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="-translate-x-full"
            >
                <div class="absolute top-0 right-0 -mr-12 pt-2">
                    <button @click="openMenu = false" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                        <span class="sr-only">Close sidebar</span>
                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <x-logo></x-logo>
                <x-nav-menu></x-nav-menu>
            </div>
            <div class="flex-shrink-0 w-14" aria-hidden="true">
                <!-- Dummy element to force sidebar to shrink to fit close icon -->
            </div>
        </div>
    </div>

    <!-- Static sidebar for desktop -->
    <div class="hidden lg:flex lg:flex-shrink-0">
        <div class="flex flex-col w-56">
            <!-- Sidebar component, swap this element with another sidebar if you like -->
            <div class="flex flex-col flex-grow bg-chocolate-700 pt-5 pb-4 overflow-y-auto">
                <x-logo></x-logo>
                <x-nav-menu></x-nav-menu>
            </div>
        </div>
    </div>

    <div class="flex-1 overflow-y-scroll focus:outline-none" tabindex="0">
        <div class="relative z-10 flex-shrink-0 flex h-16 bg-white border-b border-gray-200 lg:border-none">
            <button @click="openMenu = true" class="px-4 border-r border-gray-200 text-gray-400 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-chocolate-500 lg:hidden">
                <span class="sr-only">Open sidebar</span>
                <!-- Heroicon name: outline/menu-alt-1 -->
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" />
                </svg>
            </button>
            <!-- Search bar -->
            <div class="flex-1 px-4 flex justify-between sm:px-6 lg:max-w-7xl lg:mx-auto lg:px-8">
                <div class="flex-1 flex">
                    <form class="w-full flex md:ml-0" action="{{ route('files.index') }}" method="GET">
                        <label for="search_field" class="sr-only">Search</label>
                        <div class="relative w-full text-gray-400 focus-within:text-gray-600">
                            <div class="absolute inset-y-0 left-0 flex items-center pointer-events-none" aria-hidden="true">
                                <!-- Heroicon name: solid/search -->
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input id="search_field" name="q" value="{{ request()->get('q') }}" class="block w-full h-full pl-8 pr-3 py-2 border-transparent text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-0 focus:border-transparent sm:text-sm" placeholder="Search files" type="search">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <main class="flex-1 flex-grow relative pb-8 z-0 h-full">

            {{ $slot }}

        </main>
    </div>
</div>


</body>
</html>
